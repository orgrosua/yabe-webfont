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

namespace Yabe\Webfont\Builder\Spectra;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;

/**
 * Spectra integration.
 *
 * @author Joshua Gugun Siagian <suabahasa@gmail.com>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        /**
         * Disable Spectra's built-in Google Fonts.
         */
        add_filter('uagb_enqueue_google_fonts', static fn () => false, 1_000_001);

        add_filter('pre_option_uag_load_select_font_globally', static fn ($pre_option, $option, $default) => 'enabled', 1_000_001, 3);
        add_filter('pre_option_uag_select_font_globally', fn ($pre_option, $option, $default) => $this->select_font_globally(), 1_000_001, 3);

        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('spectra'), 1_000_001);
    }

    public function get_name(): string
    {
        return 'spectra';
    }

    public function select_font_globally()
    {
        $global_fonts = [];
        $fonts = Font::get_fonts();

        foreach ($fonts as $font) {
            $global_fonts[] = [
                'value' => $font['family'],
                'label' => '[Yabe] ' . $font['title'],
            ];
        }

        return $global_fonts;
    }
}
