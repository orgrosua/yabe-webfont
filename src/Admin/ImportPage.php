<?php

declare(strict_types=1);

/*
 * This file is part of the Yabe package.
 *
 * (c) Joshua <joshua@rosua.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yabe\Webfont\Admin;

use Yabe\Webfont\Core\Font;
use Yabe\Webfont\Plugin;

/**
 * The preview and import page for the webfont.
 *
 * @author Joshua <joshua@rosua.org>
 */
class ImportPage
{
    public function __construct()
    {
        add_action('admin_menu', fn () => $this->admin_menu(), 100);
        add_action('admin_enqueue_scripts', fn () => $this->cpt_index_enqueue_scripts(), 100);
    }

    public function cpt_index_enqueue_scripts()
    {
        $current_screen = get_current_screen();

        if (is_object($current_screen) && $current_screen->post_type === BRICKS_DB_CUSTOM_FONTS) {
            wp_enqueue_script(YABE_WEBFONT_OPTION_NAMESPACE . '-cpt-index', plugin_dir_url(YABE_WEBFONT_FILE) . 'build/cpt-index.js', ['bricks-custom-fonts'], filemtime(plugin_dir_path(YABE_WEBFONT_FILE) . 'build/cpt-index.js'), true);
            wp_localize_script(YABE_WEBFONT_OPTION_NAMESPACE . '-cpt-index', 'yabeWebfontCptIndex', [
                'action_url' => add_query_arg([
                    'page' => 'yabe-webfont-import',
                ], admin_url('admin.php')),
            ]);
        }
    }

    public function admin_menu()
    {
        $hook = add_submenu_page(
            'bricks',
            __('Import Google Fonts', 'yabe-webfont'),
            __('Import Google Fonts', 'yabe-webfont'),
            'manage_options',
            'yabe-webfont-import',
            fn () => $this->render()
        );

        add_action('load-' . $hook, fn () => $this->init_hooks());
    }

    public function render()
    {
        echo '<div id="yabe-webfont-app" class="wrap"></div>';
    }

    public function init_hooks()
    {
        add_action('admin_enqueue_scripts', fn () => $this->enqueue_scripts());
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style(YABE_WEBFONT_OPTION_NAMESPACE . '-font-import', plugin_dir_url(YABE_WEBFONT_FILE) . 'build/font-import.css', [], filemtime(plugin_dir_path(YABE_WEBFONT_FILE) . 'build/font-import.css'));
        wp_enqueue_script(YABE_WEBFONT_OPTION_NAMESPACE . '-font-import', plugin_dir_url(YABE_WEBFONT_FILE) . 'build/font-import.js', [], filemtime(plugin_dir_path(YABE_WEBFONT_FILE) . 'build/font-import.js'), true);
        wp_set_script_translations(YABE_WEBFONT_OPTION_NAMESPACE . '-font-import', 'yabe-webfont');
        wp_localize_script(YABE_WEBFONT_OPTION_NAMESPACE . '-font-import', 'yabeWebfontImport', [
            '_wpnonce' => wp_create_nonce(YABE_WEBFONT_OPTION_NAMESPACE . '-font-import'),
            'postUrl' => admin_url('post.php'),
            'mimeTypes' => array_keys(Font::get_custom_fonts_mime_types()),
            'hostedWakufont' => Font::hosted_wakufont(),
            '_version' => Plugin::VERSION,
        ]);
    }
}
