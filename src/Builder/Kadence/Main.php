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

namespace Yabe\Webfont\Builder\Kadence;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;
use Yabe\Webfont\Plugin;

/**
 * Kadence integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('kadence_theme_custom_fonts', fn ($fonts) => $this->theme_custom_fonts($fonts), 1_000_001);
        add_action('enqueue_block_editor_assets', fn () => $this->register_block_editor_assets(), 1_000_001);

        /**
         * Disable Kadence's built-in Google Fonts.
         */
        add_action('customize_controls_enqueue_scripts', fn () => $this->action_theme_customizer_google_fonts(), 1_000_001);
        add_action('customize_preview_init', fn () => $this->action_theme_customizer_preview(), 1_000_001);
        add_action('enqueue_block_editor_assets', fn () => $this->block_editor_remove_google_fonts(), 1_000_001);
    }

    public function get_name(): string
    {
        return 'kadence';
    }

    public function action_theme_customizer_google_fonts()
    {
        if (wp_script_is('kadence-customizer-controls', 'registered')) {
            wp_add_inline_script('kadence-customizer-controls', 'kadenceCustomizerControlsData.gfontvars = [];', 'before');
        }
    }

    public function action_theme_customizer_preview()
    {
        if (wp_script_is('kadence-webfont-js', 'registered')) {
            wp_dequeue_script('kadence-webfont-js');
        }
    }

    public function block_editor_remove_google_fonts()
    {
        if (wp_script_is('kadence-blocks-js', 'registered')) {
            wp_add_inline_script('kadence-blocks-js', 'kadence_blocks_params.g_fonts = [];', 'before');
            wp_add_inline_script('kadence-blocks-js', 'kadence_blocks_params.g_font_names = [];', 'before');
        }
    }

    public function register_block_editor_assets()
    {
        if (wp_script_is('kadence-blocks-js', 'registered')) {
            $fonts = [];

            foreach (Runtime::get_font_families() as $font_family) {
                $fonts[] = [
                    'label' => $font_family['title'],
                    'value' => $font_family['family'],
                    'google' => false,
                    'weights' => [
                        ['value' => '100', 'label' => 'Thin 100'],
                        ['value' => '200', 'label' => 'Extra-Light 200'],
                        ['value' => '300', 'label' => 'Light 300'],
                        ['value' => '400', 'label' => 'Regular'],
                        ['value' => '500', 'label' => 'Medium 500'],
                        ['value' => '600', 'label' => 'Semi-Bold 600'],
                        ['value' => '700', 'label' => 'Bold 700'],
                        ['value' => '800', 'label' => 'Extra-Bold 800'],
                        ['value' => '900', 'label' => 'Ultra-Bold 900'],
                    ],
                    'styles' => [
                        ['value' => 'normal', 'label' => 'Normal'],
                        ['value' => 'italic', 'label' => 'Italic'],
                    ],
                ];
            }

            wp_enqueue_script('yabe-webfont-for-kadence-blocks', plugin_dir_url(__FILE__) . '/assets/script/kadence-blocks.js', ['kadence-blocks-js'], Plugin::VERSION, true);
            wp_localize_script('yabe-webfont-for-kadence-blocks', 'yabeWebfontKadenceBlocks', [
                'fonts' => $fonts,
            ]);
        }
    }

    public function theme_custom_fonts($fonts)
    {
        $font_families = Runtime::get_font_families();

        $v = [
            '100',
            '100italic',
            '200',
            '200italic',
            '300',
            '300italic',
            'regular',
            'italic',
            '500',
            '500italic',
            '600',
            '600italic',
            '700',
            '700italic',
            '800',
            '800italic',
            '900',
            '900italic',
        ];

        foreach ($font_families as $font_family) {
            $fonts[$font_family['family']] = [
                'v' => $v,
            ];
        }

        return $fonts;
    }
}
