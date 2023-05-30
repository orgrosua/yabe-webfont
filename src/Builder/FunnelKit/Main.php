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
        $fonts = Font::get_fonts();

        $yabe_fonts = [];

        foreach ($fonts as $font) {
            $yabe_fonts[] = [
                'label' => $font['title'],
                'value' => Font::css_variable($font['family']),
                'google' => false,
            ];
        }

        return array_merge($yabe_fonts, $bwf_fonts);
    }
}
