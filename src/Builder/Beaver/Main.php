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

namespace Yabe\Webfont\Builder\Beaver;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * Beaver integration.
 *
 * @author Joshua <joshua@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('fl_theme_system_fonts', fn ($fonts) => $this->custom_fonts($fonts), 100001);
        add_filter('fl_builder_font_families_system', fn ($fonts) => $this->custom_fonts($fonts), 100001);
    }

    public function get_name(): string
    {
        return 'beaver';
    }

    public function custom_fonts($fonts)
    {
        $font_families = Runtime::get_font_families();

        foreach ($font_families as $font_family) {
            $fonts[$font_family['family']] = [
                'fallback' => 'Verdana, Arial, sans-serif',
                'weights' => ['100', '200', '300', '400', '500', '600', '700', '800', '900'],
            ];
        }

        return $fonts;
    }
}
