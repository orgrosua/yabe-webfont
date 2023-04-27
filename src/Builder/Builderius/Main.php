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

namespace Yabe\Webfont\Builder\Builderius;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * @author Joshua <id@rosua.org>
 *
 * @todo Built-in Google Fonts are not disabled.
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('builderius'), 1_000_001);

        add_action('wp_enqueue_scripts', fn () => $this->enqueue_scripts(), 1_000_001);
    }

    public function get_name(): string
    {
        return 'builderius';
    }

    public function enqueue_scripts()
    {
        if (! (isset($_GET['builderius']) && isset($_GET['builderius_template']) && ! empty($_GET['builderius_template']))) {
            return;
        }

        if (! wp_script_is('builderius-builder', 'registered')) {
            return;
        }

        $font_families = Runtime::get_font_families();

        wp_add_inline_script('builderius-builder', 'var yabeWebfontBuilderiusFamilies = ' . json_encode(array_column($font_families, 'family'), JSON_THROW_ON_ERROR), 'before');
        wp_add_inline_script('builderius-builder', "
            builderiusBackend.settingsList.module.advanced.typography[0].options.fontType.values.push('Yabe Webfont');
            builderiusBackend.settingsList.module.advanced.typography[0].options.genericFamily.values['Yabe Webfont'] = ['Yabe Webfont'];
            builderiusBackend.settingsList.module.advanced.typography[0].options.fontFamily.values['Yabe Webfont.Yabe Webfont'] = yabeWebfontBuilderiusFamilies;
        ", 'before');
    }
}
