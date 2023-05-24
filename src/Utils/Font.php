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

namespace Yabe\Webfont\Utils;

/**
 * Font utility functions for the plugin.
 *
 * @author Joshua <id@rosua.org>
 */
class Font
{
    public static function get_font_families(): array
    {
        $families = wp_cache_get('get_font_families', YABE_WEBFONT_OPTION_NAMESPACE);

        if ($families === false) {
            /** @var wpdb $wpdb */
            global $wpdb;

            $families = [];

            $sql = "
                SELECT title, family, type, slug FROM {$wpdb->prefix}yabe_webfont_fonts 
                WHERE status = 1
                    AND deleted_at IS NULL
            ";

            $result = $wpdb->get_results($sql);

            foreach ($result as $row) {
                $f = [
                    'title' => $row->title,
                    'family' => $row->family,
                    'type' => $row->type,
                    'slug' => $row->slug,
                ];

                // TODO: add CSS variable using the extracted function
                // $f['css_variable'] = '';

                $families[] = $f;
            }

            wp_cache_set('get_font_families', $families, YABE_WEBFONT_OPTION_NAMESPACE);
        }

        return $families;
    }
}