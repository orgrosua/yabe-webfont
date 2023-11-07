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

namespace Yabe\Webfont\Builder\YellowPencil;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;
use YABE_WEBFONT;

/**
 * Yellow Pencil integration.
 *
 * @author Joshua Gugun Siagian <suabahasa@gmail.com>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        // enqueue scripts on wp-admin if the current page is revslider
        add_action('yp_editor_header', fn () => $this->editor_header(), 1_000_001);

        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('yellow-pencil-changes'), 1_000_001);
    }

    public function get_name(): string
    {
        return 'yellow-pencil';
    }

    public function editor_header()
    {
        $fonts = Font::get_fonts();

        $yabe_fonts = [];

        foreach ($fonts as $font) {
            $yabe_fonts[] = [
                'value' => $font['family'],
                'label' => $font['title'],
                'category' => 'Yabe Webfont',
            ];
        }

        echo '<script src="' . plugin_dir_url(__FILE__) . 'assets/script/yellow-pencil.js?ver=' . YABE_WEBFONT::VERSION . '" id="yabe-webfont-for-yellow-pencil"></script>';
        echo '<script>var yabeFonts = ' . json_encode($yabe_fonts) . ';</script>';
    }
}
