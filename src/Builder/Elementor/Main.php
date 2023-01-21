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

namespace Yabe\Webfont\Builder\Elementor;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * Elementor integration.
 *
 * @author Joshua <joshua@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('elementor/fonts/groups', fn ($groups) => array_merge(['yabe-webfont' => 'Yabe Webfont',], $groups), 100001);
        add_filter('elementor/fonts/additional_fonts', fn ($fonts) => $this->filter_elementor_fonts($fonts), 100001);
    }

    public function get_name(): string
    {
        return 'elementor';
    }

    public function filter_elementor_fonts($fonts)
    {
        $font_families = Runtime::get_font_families();

        foreach ($font_families as $font_family) {
            $fonts[$font_family['family']] = 'yabe-webfont';
        }

        return $fonts;
    }
}
