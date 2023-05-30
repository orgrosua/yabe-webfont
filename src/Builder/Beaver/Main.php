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

namespace Yabe\Webfont\Builder\Beaver;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;

/**
 * Beaver integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('fl_builder_google_fonts_pre_enqueue', fn () => [], 1_000_001);
        add_filter('fl_builder_font_families_google', fn () => [], 1_000_001);

        add_filter('fl_builder_font_families_system', fn ($fonts) => $this->custom_fonts($fonts), 1_000_001);
    }

    public function get_name(): string
    {
        return 'beaver';
    }

    public function custom_fonts($fonts)
    {
        $font_families = Font::get_font_families();

        $families = [];

        foreach ($font_families as $font_family) {
            $families[$font_family['family']] = [
                'fallback' => '',
                'weights' => ['100', '200', '300', '400', '500', '600', '700', '800', '900'],
            ];
        }

        $fonts = array_merge($families, $fonts);

        return $fonts;
    }
}
