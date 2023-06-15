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

namespace Yabe\Webfont\Builder\Divi;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;

/**
 * Divi integration.
 *
 * @author Joshua Gugun Siagian <suabahasa@gmail.com>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        /**
         * Disable Divi's built-in Google Fonts.
         */
        add_filter('pre_option_et_google_api_settings', fn ($pre_option, $option, $default) => $this->et_google_api_settings($pre_option), 1_000_001, 3);
        add_filter('pre_update_option_et_google_api_settings', fn ($value, $old_value, $option) => $this->et_google_api_settings($value), 1_000_001, 3);

        add_filter('et_websafe_fonts', fn ($fonts) => $this->filter_divi_fonts($fonts), 1_000_001);
        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('et_divi_options'), 1_000_001);
    }

    public function get_name(): string
    {
        return 'divi';
    }

    public function filter_divi_fonts($divi_fonts)
    {
        $fonts = Font::get_fonts();

        foreach ($fonts as $font) {
            $divi_fonts[$font['family']] = [
                'type' => 'serif',
            ];
        }

        return $divi_fonts;
    }

    public function et_google_api_settings($opt)
    {
        $opt['use_google_fonts'] = 'false';
        return $opt;
    }
}
