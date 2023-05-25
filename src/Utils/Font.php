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
                    'css' => [
                        'slug' => self::slugify($row->family),
                        'custom_property' => self::css_custom_property($row->family),
                        'variable' => self::css_variable($row->family),
                    ],
                    'variants' => [],
                    'fallback_family' => null,
                ];

                foreach ($$row->font_faces as $font_face) {
                    $f['variants'][] = [
                        'weight' => $font_face->weight,
                        'style' => $font_face->style,
                    ];
                }

                $selectorParts = [];

                // if property selector is exists
                if (property_exists($metadata, 'selector') && $metadata->selector) {
                    $selectorParts = explode('|', $metadata->selector);
                    $selectorParts = array_map('trim', $selectorParts);
                    $selectorParts = array_filter($selectorParts);

                    $f['fallback_family'] = isset($selectorParts[1]) ? $selectorParts[1] : null;
                }

                $families[] = $f;
            }

            wp_cache_set('get_font_families', $families, YABE_WEBFONT_OPTION_NAMESPACE);
        }

        return $families;
    }

    /**
     * @param string $value font family name
     * @return string css custom property wrapped with variable function. e.g. `var(--ywf--family-open-sans)` for `Open Sans`
     */
    public static function css_variable(string $value): string
    {
        return sprintf('var(%s)', self::css_custom_property($value));
    }

    /**
     * @param string $value font family name
     * @return string css custom property. e.g. `--ywf--family-open-sans` for `Open Sans`
     */
    public static function css_custom_property(string $value): string
    {
        return sprintf('--ywf--family-%s', self::slugify($value));
    }

    /**
     * @param string $value font family name
     * @return string slugified string. e.g. `open-sans` for `Open Sans`
     */
    public static function slugify(string $value): string
    {
        return preg_replace('#[^a-zA-Z0-9\-_]+#', '-', strtolower($value));
    }
}
