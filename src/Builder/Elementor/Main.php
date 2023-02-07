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

namespace Yabe\Webfont\Builder\Elementor;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * Elementor integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('elementor/fonts/groups', static fn ($groups) => array_merge([
            'yabe-webfont' => 'Yabe Webfont',
        ], $groups), 1_000_001);
        add_filter('elementor/fonts/additional_fonts', fn ($fonts) => $this->filter_elementor_fonts($fonts), 1_000_001);
        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('elementor'), 1_000_001);
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
