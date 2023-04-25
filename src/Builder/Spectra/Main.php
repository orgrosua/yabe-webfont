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

namespace Yabe\Webfont\Builder\Spectra;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * Spectra integration.
 *
 * @author Joshua <id@rosua.org>
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
        $font_families = Runtime::get_font_families();

        foreach ($font_families as $font_family) {
            $global_fonts[] = [
                'value' => $font_family['family'],
                'label' => '[Yabe] ' . $font_family['title'],
            ];
        }

        return $global_fonts;
    }
}
