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

namespace Yabe\Webfont\Builder\GeneratePress;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * GeneratePress integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        /**
         * Disable GeneratePress's built-in Google Fonts.
         */
        add_filter('generate_font_manager_show_google_fonts', static fn () => false, 1_000_001, 0);

        /**
         * @see https://github.com/tomusborne/generatepress/blob/e7fbf5693bfe4325a41cae988e3eda16550d4025/inc/defaults.php#L412
         */
        add_filter('generate_typography_default_fonts', static fn ($fonts) => array_merge($fonts, array_column(Runtime::get_font_families(), 'family')), 1_000_001);

        add_filter('option_generate_settings', fn ($value, $option) => $this->generate_settings($value), 1_000_001, 2);
        add_filter('pre_update_option_generate_settings', fn ($value, $old_value, $option) => $this->generate_settings($value), 1_000_001, 3);
    }

    public function get_name(): string
    {
        return 'generate-press';
    }

    public function generate_settings($opt)
    {
        $font_families = Runtime::get_font_families();

        if (! is_array($opt)) {
            $opt = [];
        }

        if (! array_key_exists('font_manager', $opt) || ! is_array($opt['font_manager'])) {
            $opt['font_manager'] = [];
        }

        foreach ($font_families as $font_family) {
            if (! in_array($font_family['family'], array_column($opt['font_manager'], 'fontFamily'), true)) {
                $opt['font_manager'][] = [
                    'fontFamily' => $font_family['family'],
                    'googleFont' => false,
                    'googleFontApi' => 0,
                ];
            }
        }
        return $opt;
    }
}
