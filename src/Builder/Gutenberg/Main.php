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

namespace Yabe\Webfont\Builder\Gutenberg;

use WP_Theme_JSON_Data;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Cache;
use Yabe\Webfont\Core\Frontpage;
use Yabe\Webfont\Utils\Font;

/**
 * Gutenberg integration.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/
 * @see https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/theme-json-living/
 * @see https://developer.wordpress.org/themes/advanced-topics/theme-json/
 * @see https://make.wordpress.org/core/2022/10/10/filters-for-theme-json-data/
 * @see https://make.wordpress.org/core/2021/09/28/implementing-a-webfonts-api-in-wordpress-core/
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('wp_theme_json_data_theme', fn ($theme_json) => $this->filter_theme_json_data_theme($theme_json), 1_000_001);
        add_filter('wp_theme_json_data_user', fn ($theme_json) => $this->filter_theme_json_data_user($theme_json), 1_000_001);
        add_filter('f!yabe/webfont/core/cache:build_css.append_content', fn ($css, $rows) => $this->filter_append_build_css_content($css, $rows), 1_000_001, 2);

        add_action('enqueue_block_editor_assets', fn () => $this->enqueue_block_editor_assets(), 1_000_001);
        add_action('after_setup_theme', fn () => $this->after_setup_theme(), 1_000_001);
    }

    public function get_name(): string
    {
        return 'gutenberg';
    }

    /**
     * @see https://make.wordpress.org/core/2022/10/10/filters-for-theme-json-data/
     * @param WP_Theme_JSON_Data $theme_json
     * @return WP_Theme_JSON_Data
     */
    public function filter_theme_json_data_user($theme_json)
    {
        $theme_json_data = $theme_json->get_data();

        $theme_json_font_families = $theme_json_data['settings']['typography']['fontFamilies'] ?? [];

        $fonts = Font::get_fonts();

        foreach ($fonts as $font) {
            /**
             * @see https://www.w3.org/TR/CSS22/syndata.html#value-def-identifier
             */
            $theme_json_font_families[] = [
                'name' => sprintf('[Yabe] %s', $font['title']),
                'slug' => Font::slugify($font['family']),
                'fontFamily' => Font::css_variable($font['family']),
            ];
        }

        $new_data = [
            'version' => 2,
            'settings' => [
                'typography' => [
                    'fontFamilies' => $theme_json_font_families,
                ],
            ],
        ];

        return $theme_json->update_with($new_data);
    }

    /**
     * @see https://make.wordpress.org/core/2022/10/10/filters-for-theme-json-data/
     * @param WP_Theme_JSON_Data $theme_json
     * @return WP_Theme_JSON_Data
     */
    public function filter_theme_json_data_theme($theme_json)
    {
        $theme_json_data = $theme_json->get_data();

        $theme_json_font_families = $theme_json_data['settings']['typography']['fontFamilies']['theme'] ?? [];

        $fonts = Font::get_fonts();

        foreach ($fonts as $font) {
            /**
             * @see https://www.w3.org/TR/CSS22/syndata.html#value-def-identifier
             */
            $theme_json_font_families[] = [
                'name' => sprintf('[Yabe] %s', $font['title']),
                'slug' => Font::slugify($font['family']),
                'fontFamily' => Font::css_variable($font['family']),
            ];
        }

        $new_data = [
            'version' => 2,
            'settings' => [
                'typography' => [
                    'fontFamilies' => [
                        'theme' => $theme_json_font_families,
                    ],
                ],
            ],
        ];

        return $theme_json->update_with($new_data);
    }

    public function enqueue_block_editor_assets()
    {
        $screen = get_current_screen();
        if (is_admin() && $screen->is_block_editor()) {
            add_action('admin_head', static fn () => Frontpage::enqueue_css_cache(), 1_000_001);
        }
    }

    public function after_setup_theme()
    {
        add_editor_style(Cache::get_cache_url(Cache::CSS_CACHE_FILE));
    }

    /**
     * Support for a non block-based theme.
     */
    public function filter_append_build_css_content($css, $rows)
    {
        if (function_exists('wp_is_block_theme') && wp_is_block_theme()) {
            return $css;
        }

        return $css . self::non_block_based_theme_support_classes();
    }

    public static function non_block_based_theme_support_classes(): string
    {
        $inline_css = '';

        $fonts = Font::get_fonts();

        foreach ($fonts as $font) {
            $inline_css .= sprintf(".has-%s-font-family {\n", Font::slugify($font['family']));
            $inline_css .= sprintf("\tfont-family: %s !important;\n", Font::css_variable($font['family']));
            $inline_css .= "}\n\n";
        }

        return $inline_css;
    }
}
