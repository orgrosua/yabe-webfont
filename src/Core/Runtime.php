<?php

/*
 * This file is part of the Yabe package.
 *
 * (c) Joshua <id@rosua.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Yabe\Webfont\Core;

use Yabe\Webfont\Utils\Upload;

/**
 * @author Joshua <id@rosua.org>
 */
class Runtime
{
    public function __construct()
    {
        new Frontpage();
    }

    public static function refresh_font_faces_attachment_url(array $font_faces): array
    {
        foreach ($font_faces as $i => $font_face) {
            foreach ($font_face->files as $j => $file) {
                $attachment_url = wp_get_attachment_url($file->attachment_id);
                if ($attachment_url) {
                    $font_faces[$i]->files[$j]->attachment_url = $attachment_url;
                }
            }
        }

        return $font_faces;
    }

    public static function refresh_google_fonts_attachment_url(array $font_files): array
    {
        foreach ($font_files as $i => $font_file) {
            if (property_exists($font_file, 'file')) {
                $attachment_url = wp_get_attachment_url($font_file->file->attachment_id);
                if ($attachment_url) {
                    $font_files[$i]->file->attachment_url = $attachment_url;
                }
            }
        }

        return $font_files;
    }

    public static function build_css(): string
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $css = '';

        $sql = "
            SELECT * FROM {$wpdb->prefix}yabe_webfont_fonts 
            WHERE status = 1
                AND deleted_at IS NULL
        ";

        $result = $wpdb->get_results($sql);

        if (empty($result)) {
            return $css;
        }

        $format_precedence = [
            'woff2' => 1,
            'woff' => 2,
            'ttf' => 3,
            'otf' => 4,
            'eot' => 5,
        ];

        foreach ($result as $row) {
            $metadata = json_decode($row->metadata, null, 512, JSON_THROW_ON_ERROR);
            $font_faces = self::refresh_font_faces_attachment_url(json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR));

            foreach ($font_faces as $font_face) {
                if ($font_face->comment) {
                    $css .= "/* {$font_face->comment} */\n";
                }

                $css .= "@font-face {\n";

                $css .= "\tfont-family: '{$row->family}';\n";

                $css .= "\tfont-style: {$font_face->style};\n";

                $wght = $font_face->weight ?: '400';
                $wdth = $font_face->width ?: '100%';

                $css .= "\tfont-weight: {$wght};\n";

                $css .= "\tfont-stretch: {$wdth};\n";

                $display = $font_face->display ?: $metadata->display;

                $css .= "\tfont-display: {$display};\n";

                if ($font_face->files !== []) {
                    usort($font_face->files, static fn ($a, $b) => $format_precedence[$a->extension] <=> $format_precedence[$b->extension]);

                    $css .= "\tsrc: ";

                    $files = array_map(static fn ($f) => sprintf("url('%s') format(\"%s\")", $f->attachment_url, Upload::mime_keyword($f->extension)), $font_face->files);

                    $css .= implode(",\n\t\t", $files);

                    $css .= ";\n";
                }

                if ($font_face->unicodeRange) {
                    $css .= "\tunicode-range: {$font_face->unicodeRange};\n";
                }

                $css .= "}\n\n";
            }

            if ($metadata->selector) {
                $css .= "{$metadata->selector} {\n\tfont-family: '{$row->family}';\n}\n\n";
            }

            foreach ($font_faces as $font_face) {
                if ($font_face->selector) {
                    $css .= "{$font_face->selector} {\n";
                    $css .= "\tfont-family: '{$row->family}';\n";
                    $css .= "\tfont-style: {$font_face->style};\n";
                    $css .= "\tfont-weight: {$font_face->weight};\n";
                    $css .= "}\n\n";
                }
            }
        }

        if ($result !== []) {
            $css .= "body {\n";

            $families = [];

            foreach ($result as $row) {
                $families[] = $row->family;
            }

            $families = array_unique($families);

            foreach ($families as $family) {
                $value = sprintf("'%s'", $family);
                $name = sprintf('--yabe-webfont--family--%s', preg_replace('#[^a-zA-Z0-9\-_]+#', '-', strtolower($family)));

                $css .= "\t{$name}: {$value};\n";
            }

            $css .= "}\n\n";
        }

        /**
         * @param string $css The CSS content
         * @param array $result The result of the SQL query
         * @return string The CSS content
         */
        $css = apply_filters('f!yabe/webfont/core/runtime:append_build_css_content', $css, $result);

        if (defined('WP_DEBUG') && WP_DEBUG) {
            // replace tabs with 2 spaces
            $css = preg_replace('#\t#', '  ', $css);
        } else {
            // remove new lines and tabs
            $css = preg_replace('#\n#', '', $css);
            $css = preg_replace('#\t#', '', $css);
        }

        return $css;
    }

    public static function build_preload(): string
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $html = '';

        $sql = "
            SELECT metadata, font_faces FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE status = 1
                AND deleted_at IS NULL
        ";

        $result = $wpdb->get_results($sql);

        if (empty($result)) {
            return $html;
        }

        $preload_files = [];

        foreach ($result as $row) {
            $font_faces = self::refresh_font_faces_attachment_url(json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR));
            $metadata = json_decode($row->metadata, null, 512, JSON_THROW_ON_ERROR);

            foreach ($font_faces as $font_face) {
                if ($metadata->preload || (property_exists($font_face, 'preload') && $font_face->preload)) {
                    foreach ($font_face->files as $file) {
                        $preload_files[] = [
                            'href' => $file->attachment_url,
                            'type' => $file->mime,
                        ];
                    }
                }
            }
        }

        $preload_files = array_unique($preload_files, SORT_REGULAR);

        foreach ($preload_files as $preload_file) {
            $html .= sprintf(
                '<link rel="preload" href="%s" as="font" type="%s" crossorigin>' . PHP_EOL,
                $preload_file['href'],
                $preload_file['type']
            );
        }

        return $html;
    }

    public static function get_font_families(): array
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $families = [];

        $sql = "
            SELECT title, family FROM {$wpdb->prefix}yabe_webfont_fonts 
            WHERE status = 1
                AND deleted_at IS NULL
        ";

        $result = $wpdb->get_results($sql);

        foreach ($result as $row) {
            $families[] = [
                'title' => $row->title,
                'family' => $row->family,
            ];
        }

        return $families;
    }
}
