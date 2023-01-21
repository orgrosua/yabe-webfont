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
        add_action('elementor/controls/controls_registered', fn ($controls_manager) => $this->controls_registered($controls_manager), 100001);
    }

    public function get_name(): string
    {
        return 'elementor';
    }

    public function controls_registered($controls_manager)
    {
        // $elementor_fonts = $controls_manager->get_control('font')->get_settings('options');

        /**
         * @see \Elementor\Fonts::get_native_fonts()
         */
        $elementor_fonts = [
            'Arial' => 'system',
            'Tahoma' => 'system',
            'Verdana' => 'system',
            'Helvetica' => 'system',
            'Times New Roman' => 'system',
            'Trebuchet MS' => 'system',
            'Georgia' => 'system',
        ];

        $font_families = Runtime::get_font_families();

        foreach ($font_families as $font_family) {
            $elementor_fonts[$font_family['family']] = 'system';
        }

        $controls_manager->get_control('font')->set_settings('options', $elementor_fonts);
    }
}
