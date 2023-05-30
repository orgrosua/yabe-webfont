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

namespace Yabe\Webfont\Builder\FunnelKit;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;

/**
 * FunnelKit integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        // SlingBlocks
        add_filter('bwf_custom_system_font', fn ($f) => $this->add_block_fonts($f), 1_000_001);
    }

    public function get_name(): string
    {
        return 'funnelkit';
    }

    private function add_block_fonts(array $bwf_fonts): array
    {
        $font_families = Font::get_font_families();

        $fonts = [];

        foreach ($font_families as $font_family) {
            $fonts[] = [
                'label' => $font_family['title'],
                'value' => Font::css_variable($font_family['family']),
                'google' => false,
            ];
        }

        return array_merge($fonts, $bwf_fonts);
    }
}
