<?php

/*
 * This file is part of the Yabe package.
 *
 * (c) Joshua <joshua@rosua.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Yabe\Webfont\Core;

use Bricks\Capabilities;
use WP_Error;

/**
 * Font resource provider.
 *
 * @author Joshua <joshua@rosua.org>
 */
class Font
{
    public function __construct()
    {
        add_action('wp_ajax_yabe_webfont_import_selected_font', fn () => $this->ajax_action_import_selected_font());
    }

    public static function hosted_wakufont(): string
    {
        return rtrim(apply_filters('f!yabe/webfont/font:wakufont_self_hosted', defined('YABE_SELF_HOSTED_WAKUFONT') ? constant('YABE_SELF_HOSTED_WAKUFONT') : YABE_WEBFONT_HOSTED_WAKUFONT), '/');
    }

    /**
     * Fetch the font files from the respective API.
     *
     * @param string $slug the font slug
     * @return \WP_Error|\stdClass
     */
    public function fetch_font_item($slug, $subsets)
    {
        $item_hash = substr(wp_hash($slug . '_' . str_replace(',', '_', $subsets), 'nonce'), -12, 10);

        $font_item = get_transient(YABE_WEBFONT_OPTION_NAMESPACE . '_font_item_' . $item_hash);

        if ($font_item) {
            return $font_item;
        }

        $res = wp_remote_get(sprintf(
            self::hosted_wakufont() . '/api/fonts/%s?subsets=%s',
            $slug,
            $subsets
        ));

        if (is_wp_error($res)) {
            return $res;
        }

        $body = wp_remote_retrieve_body($res);

        if (is_wp_error($body)) {
            return $body;
        }

        $font_item = json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('json_decode_error', json_last_error_msg());
        }

        set_transient(YABE_WEBFONT_OPTION_NAMESPACE . '_font_item_' . $item_hash, $font_item, 15 * MINUTE_IN_SECONDS);

        return $font_item;
    }

    /**
     * Ajax action for importing the selected font.
     */
    public function ajax_action_import_selected_font()
    {
        check_ajax_referer(YABE_WEBFONT_OPTION_NAMESPACE . '-font-import');

        add_filter('upload_mimes', fn ($mime_types) => $this->upload_mimes($mime_types));

        $selected_slug = $_REQUEST['font_slug'];
        $selected_subset = $_REQUEST['subsets'];
        $selected_variant = explode(',', $_REQUEST['variants']);

        $font_item = $this->fetch_font_item($selected_slug, $selected_subset);

        if (is_wp_error($font_item)) {
            wp_send_json_error($font_item->get_error_message());
        }

        $postarr = [
            'post_type' => 'bricks_fonts',
            'post_title' => sanitize_text_field($font_item->font->family),
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
        ];

        if (in_array('0', $selected_variant, true) || in_array('0i', $selected_variant, true)) {
            $this->import_selected_variable_font($font_item, $postarr, $selected_variant, $selected_subset);
        } else {
            $this->import_selected_font($font_item, $postarr, $selected_variant);
        }
    }

    /**
     * @see Bricks\Custom_Fonts::upload_mimes()
     */
    public function upload_mimes($mime_types)
    {
        if (Capabilities::current_user_can_use_builder()) {
            foreach (static::get_custom_fonts_mime_types() as $type => $mime) {
                if (! isset($mime_types[$type])) {
                    $mime_types[$type] = $mime;
                }
            }
        }

        return $mime_types;
    }

    /**
     * @see Bricks\Custom_Fonts::get_custom_fonts_mime_types()
     */
    public static function get_custom_fonts_mime_types()
    {
        $font_mime_types = [
            // 'eot'   => 'font/eot', // <IE9 only (if specified, it must be listed first)
            'woff2' => 'font/woff2',
            'woff' => 'font/woff',
            'ttf' => 'font/ttf',
        ];

        // NOTE: Undocumented
        return apply_filters('bricks/custom_fonts/mime_types', $font_mime_types);
    }

    /**
     * Upload font's file to WordPress media library.
     * The implementation is based on the https://rudrastyh.com/wordpress/how-to-add-images-to-media-library-from-uploaded-files-programmatically.html#upload-image-from-url
     *
     * @param string $font_url URL of the font file
     * @param string $font_name Name of the font file to be stored in the media library
     * @param string $mime_type Mime type of the font file
     * @return int|WP_Error|false Attachment ID on success, WP_Error or false on failure
     */
    public function upload_font(string $font_url, string $font_name, string $mime_type)
    {
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        $temp_file = download_url($font_url);

        if (is_wp_error($temp_file)) {
            return false;
        }

        $file = [
            'name' => $font_name,
            'type' => $mime_type,
            'tmp_name' => $temp_file,
            'size' => filesize($temp_file),
        ];

        $sideload = wp_handle_sideload($file, [
            'test_form' => false,
            'test_size' => false,
        ]);

        if (! empty($sideload['error'])) {
            // you may return error message if you want

            return false;
        }

        // it is time to add our uploaded image into WordPress media library
        $attachment_id = wp_insert_attachment(
            [
                'guid' => $sideload['url'],
                'post_mime_type' => $sideload['type'],
                'post_title' => basename($sideload['file']),
                'post_content' => '',
                'post_status' => 'inherit',
            ],
            $sideload['file']
        );

        if (is_wp_error($attachment_id) || ! $attachment_id) {
            return false;
        }

        try {
            if (file_exists($temp_file)) {
                unlink($temp_file);
            }
        } catch (\Throwable $throwable) {
            //throw $th;
        }

        return $attachment_id;
    }

    private function import_selected_font($font_item, $postarr, $selected_variant)
    {
        $font_file = null;
        $post_id = null;

        $selected_mime = explode(',', $_REQUEST['mimes']);
        $post_attachments = [];

        $mime_types = static::get_custom_fonts_mime_types();

        $post_id = wp_insert_post($postarr);

        foreach ($font_item->files as $font_file) {
            $variant_key = sprintf('%s%s', $font_file->weight, $font_file->style === 'italic' ? 'i' : '');

            if (! in_array($variant_key, $selected_variant, true)) {
                continue;
            }

            if (! in_array($font_file->format, $selected_mime, true)) {
                continue;
            }

            $font_name = sanitize_title_with_dashes(sprintf(
                '%s-%s-%s-%s-%s',
                $font_item->font->slug,
                (new \DateTime($font_item->font->modifiedAt))->format('Ymd'),
                implode('-', $font_file->subsets),
                $variant_key,
                time()
            )) . '.' . $font_file->format;

            $mime_type = $mime_types[$font_file->format];

            $uploaded_file = $this->upload_font($font_file->url, $font_name, $mime_type);

            if (! $uploaded_file || is_wp_error($uploaded_file)) {
                // TODO: replace with better error handling
                continue;
            }

            $variant_long_key = sprintf('%s%s', $font_file->weight, $font_file->style === 'normal' ? '' : $font_file->style);
            $post_attachments[$variant_long_key][$font_file->format] = $uploaded_file;
        }

        update_post_meta($post_id, BRICKS_DB_CUSTOM_FONT_FACES . '_subsets', $font_file->subsets);

        $updated = update_post_meta($post_id, BRICKS_DB_CUSTOM_FONT_FACES, $post_attachments);

        if ($updated) {
            wp_send_json_success([$post_id]);
        } else {
            wp_send_json_error();
        }
    }

    private function import_selected_variable_font($font_item, $postarr, $selected_variant, $selected_subsets)
    {
        $post_ids = [];

        $selected_subsets = explode(',', $selected_subsets);

        $mime_types = static::get_custom_fonts_mime_types();

        $weight_axis = array_filter($font_item->font->axes, static fn ($axis) => $axis->tag === 'wght')[0];

        foreach ($selected_subsets as $selected_subset) {
            $post_attachments = [];

            $post_id = wp_insert_post($postarr);

            foreach ($font_item->files as $font_file) {
                if ($font_file->weight !== 0) {
                    continue;
                }

                if ($font_file->style === 'normal' && ! in_array('0', $selected_variant, true)) {
                    continue;
                }

                if ($font_file->style === 'italic' && ! in_array('0i', $selected_variant, true)) {
                    continue;
                }

                if (! in_array($selected_subset, $font_file->subsets, true)) {
                    continue;
                }

                $font_name = sanitize_title_with_dashes(sprintf(
                    '%s-%s-%s-%s-%s',
                    $font_item->font->slug,
                    (new \DateTime($font_item->font->modifiedAt))->format('Ymd'),
                    implode('-', $font_file->subsets),
                    $font_file->weight . ($font_file->style === 'italic' ? 'i' : ''),
                    time()
                )) . '.' . $font_file->format;

                $mime_type = $mime_types[$font_file->format];

                $uploaded_file = $this->upload_font($font_file->url, $font_name, $mime_type);

                if (! $uploaded_file || is_wp_error($uploaded_file)) {
                    // TODO: replace with better error handling
                    continue;
                }

                $curr_weight = $weight_axis->min;
                while ($curr_weight <= $weight_axis->max) {
                    $variant_long_key = sprintf('%s%s', $curr_weight, $font_file->style === 'normal' ? '' : $font_file->style);
                    $post_attachments[$variant_long_key][$font_file->format] = $uploaded_file;

                    $curr_weight += (int) sanitize_text_field($_REQUEST['weight_step']) ?: 100;
                }

                update_post_meta($post_id, BRICKS_DB_CUSTOM_FONT_FACES . '_subsets', [$selected_subset]);
                update_post_meta($post_id, BRICKS_DB_CUSTOM_FONT_FACES, $post_attachments);
            }

            $post_ids[] = $post_id;
        }

        if ($post_ids !== []) {
            wp_send_json_success($post_ids);
        } else {
            wp_send_json_error();
        }
    }
}
