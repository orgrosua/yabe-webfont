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

namespace Yabe\Webfont\Builder\FunnelKit;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;

/**
 * FunnelKit integration.
 *
 * @author Joshua Gugun Siagian <suabahasa@gmail.com>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        /**
         * Disable SlingBlocks' built-in Google Fonts.
         */
        add_filter('bwf_custom_google_font', static fn () => [], 1_000_001);
        add_filter('bwf_custom_google_font_names_list', static fn () => [], 1_000_001);
        add_action('enqueue_block_editor_assets', fn () => $this->remove_google_fonts_list(), 1_000_001);
        add_action('wp_print_scripts', fn () => $this->dequeue_webfont(), 1_000_001);

        // SlingBlocks
        add_filter('bwf_custom_system_font', fn ($f) => $this->add_block_fonts($f), 1_000_001);
    }

    public function get_name(): string
    {
        return 'funnelkit';
    }

    private function add_block_fonts(array $bwf_fonts): array
    {
        $fonts = Font::get_fonts();

        $yabe_fonts = [];

        foreach ($fonts as $font) {
            $yabe_fonts[] = [
                'label' => '[Yabe] ' . $font['title'],
                'value' => Font::css_variable($font['family']),
                'google' => false,
            ];
        }

        return array_merge($yabe_fonts, $bwf_fonts);
    }

    private function remove_google_fonts_list()
    {
        if (! defined('SLINGBLOCKS_PLUGIN_VERSION')) {
            return;
        }

        $screen = get_current_screen();
        if (is_admin() && $screen->is_block_editor()) {
            if (! wp_script_is('slingblocks-editor', 'registered')) {
                return;
            }

            wp_add_inline_script('slingblocks-editor', 'const WebFont = { load: () => {} }', 'before');
        }
    }

    private function dequeue_webfont()
    {
        if (! defined('SLINGBLOCKS_PLUGIN_VERSION')) {
            return;
        }

        wp_dequeue_script('web-font');
    }
}
