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

namespace Yabe\Webfont\Builder\Bricks;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        /**
         * Prevent Google Fonts loading
         * @see https://academy.bricksbuilder.io/article/filter-bricks-assets-load_webfonts/
         */
        add_filter('bricks/assets/load_webfonts', '__return_false', 1_000_001);

        /**
         * @see https://academy.bricksbuilder.io/article/filter-standard-fonts/
         */
        // add_filter('bricks/builder/standard_fonts', static fn ($fonts) => array_merge($fonts, array_column(Runtime::get_font_families(), 'family')), 1_000_001);

        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('bricks'), 1_000_001);

        add_action('wp_enqueue_scripts', fn () => $this->enqueue_scripts(), 1_000_001);
    }

    public function get_name(): string
    {
        return 'bricks';
    }

    public function enqueue_scripts()
    {
        if (wp_script_is('bricks-builder', 'registered') && bricks_is_builder()) {
            $yabeOptions = [];
            $font_families = Runtime::get_font_families();
            foreach ($font_families as $font_family) {
                $yabeOptions[$font_family['family']] = $font_family['title'];
            }
            wp_add_inline_script('bricks-builder', 'var yabeOptions = ' . json_encode($yabeOptions, JSON_THROW_ON_ERROR), 'before');
            wp_add_inline_script('bricks-builder', 'bricksData.loadData.fonts.yabe = ' . json_encode(array_column($font_families, 'family'), JSON_THROW_ON_ERROR), 'before');
            wp_add_inline_script('bricks-builder', "bricksData.loadData.fonts.options = { ...{'yabeFontsGroupTitle': 'Yabe Webfonts' }, ...yabeOptions, ...bricksData.loadData.fonts.options};", 'before');
        }
    }
}
