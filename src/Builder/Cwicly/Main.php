<?php

/*
 * This file is part of the Yabe package.
 *
 * (c) Joshua Gugun Siagian <suabahasa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Yabe\Webfont\Builder\Cwicly;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;

/**
 * Cwicly integration.
 *
 * @author Joshua Gugun Siagian <suabahasa@gmail.com>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_action('enqueue_block_editor_assets', fn () => $this->enqueue_block_editor_assets(), 1_000_001);

        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('cwicly'), 1_000_001);
    }

    public function get_name(): string
    {
        return 'cwicly';
    }

    public function enqueue_block_editor_assets()
    {
        if (!wp_script_is('cwicly_editor_blocks', 'registered')) {
            return;
        }

        $fonts = Font::get_fonts();

        $localFonts = [];

        foreach ($fonts as $font) {
            $key = sprintf('custom-yabe-%s', Font::slugify($font['family']));

            $localFonts[$key] = [
                'family' => $font['family'],
                'fonts' => [],
                'type' => 'custom',
                'category' => 'Sans Serif',
                'display' => 'swap',
                'subsets' => [],
                'axes' => [],
            ];
        }

        wp_add_inline_script('cwicly_editor_blocks', 'var yabeWebfontCwiclyLocalFonts = ' . json_encode($localFonts, JSON_THROW_ON_ERROR), 'before');

        wp_add_inline_script('cwicly_editor_blocks', "
            if (typeof cwicly_info.starters.localfonts !== 'object') {
                cwicly_info.starters.localfonts = {};
            }

            if (!Array.isArray(cwicly_info.starters.localactivefonts)) {
                cwicly_info.starters.localactivefonts = [];
            }

            Object.entries(yabeWebfontCwiclyLocalFonts).forEach(function ([key, value]) {
                cwicly_info.starters.localfonts[key] = value;
                cwicly_info.starters.localactivefonts.push(key);
            });

            setTimeout(function () {
                wp.data.dispatch('cwicly/base').writeLocalActiveFonts(cwicly_info.starters.localactivefonts);
                wp.data.dispatch('cwicly/base').writeLocalFonts(cwicly_info.starters.localfonts);
            }, 1000);
        ", 'before');
    }
}
