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

namespace Yabe\Webfont\Builder\Gutenberg;

use WP_Theme_JSON_Data;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Frontpage;
use Yabe\Webfont\Core\Runtime;

/**
 * Gutenberg integration.
 * 
 * @see https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/
 * @see https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/theme-json-living/
 * @see https://developer.wordpress.org/themes/advanced-topics/theme-json/
 * @see https://make.wordpress.org/core/2022/10/10/filters-for-theme-json-data/
 * @see https://make.wordpress.org/core/2021/09/28/implementing-a-webfonts-api-in-wordpress-core/
 * 
 * @author Joshua <joshua@rosua.org>
 */
class Main implements BuilderInterface
{
    public function get_name(): string
    {
        return 'gutenberg';
    }

    public function __construct()
    {
        add_filter('wp_theme_json_data_user', fn ($theme_json) => $this->filter_theme_json_theme($theme_json), 100001);

        add_action('enqueue_block_editor_assets', fn () => $this->enqueue_block_editor_assets());
    }

    /**
     * @see https://make.wordpress.org/core/2022/10/10/filters-for-theme-json-data/
     * @param WP_Theme_JSON_Data $theme_json
     * @return WP_Theme_JSON_Data
     */
    public function filter_theme_json_theme($theme_json)
    {
        $theme_json_data = $theme_json->get_data();

        $theme_json_font_families = isset($theme_json_data['settings']['typography']['fontFamilies'])
            ? $theme_json_data['settings']['typography']['fontFamilies']
            : [];

        $font_families = Runtime::get_font_families();

        foreach ($font_families as $row) {
            /**
             * @see https://www.w3.org/TR/CSS22/syndata.html#value-def-identifier
             */
            $theme_json_font_families[] = [
                'name' => "[Yabe] {$row['title']}",
                'slug' => preg_replace('/[^a-zA-Z0-9\-_]+/', '-', $row['family']),
                'fontFamily' => $row['family'],
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

    public function enqueue_block_editor_assets()
    {
        $screen = get_current_screen();
        if (is_admin() && $screen->is_block_editor()) {
            Frontpage::enqueue_css_cache();
        }
    }
}
