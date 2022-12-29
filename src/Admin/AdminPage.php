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

namespace Yabe\Webfont\Admin;

use Yabe\Webfont\Plugin;
use Yabe\Webfont\Utils\Upload;

use function wp_enqueue_media;

class AdminPage
{
    public function __construct()
    {
        add_filter('wp_check_filetype_and_ext', fn ($data, $file, $filename, $mimes) => Upload::disable_real_mime_check($data, $file, $filename, $mimes), 10, 4);
        add_filter('upload_mimes', fn ($mime_types) => Upload::upload_mimes($mime_types), 10001);

        add_action('admin_menu', [$this, 'add_admin_menu']);

        // \Yabe\Webfont\Utils\Notice::info('<p>info Plugin version: ' . Plugin::VERSION, 'version_info</p>');
        // \Yabe\Webfont\Utils\Notice::success('success Plugin version: ' . Plugin::VERSION, 'version_success');
        // \Yabe\Webfont\Utils\Notice::warning('warning Plugin version: ' . Plugin::VERSION, 'version_warning');
        // \Yabe\Webfont\Utils\Notice::error('error Plugin version: ' . Plugin::VERSION, 'version_error');
    }

    public function add_admin_menu()
    {
        $hook = add_menu_page(
            __('Yabe Webfont', 'yabe-webfont'),
            __('Yabe Webfont', 'yabe-webfont'),
            'manage_options',
            YABE_WEBFONT_OPTION_NAMESPACE,
            fn () => $this->render(),
            'dashicons-editor-textcolor'
        );

        add_action('load-' . $hook, fn () => $this->init_hooks());
    }

    public function render()
    {
        echo '<div id="yabe-webfont-app" class=""></div>';
    }

    public function init_hooks()
    {
        add_action('admin_enqueue_scripts', fn () => $this->enqueue_scripts());
    }

    public function enqueue_scripts()
    {
        wp_enqueue_media();

        wp_enqueue_style(YABE_WEBFONT_OPTION_NAMESPACE . '-app', plugin_dir_url(YABE_WEBFONT_FILE) . 'build/app.css', [], filemtime(plugin_dir_path(YABE_WEBFONT_FILE) . 'build/app.css'));
        wp_enqueue_script(YABE_WEBFONT_OPTION_NAMESPACE . '-app', plugin_dir_url(YABE_WEBFONT_FILE) . 'build/app.js', [], filemtime(plugin_dir_path(YABE_WEBFONT_FILE) . 'build/app.js'), true);

        wp_set_script_translations(YABE_WEBFONT_OPTION_NAMESPACE . '-app', 'yabe-webfont');
        wp_localize_script(YABE_WEBFONT_OPTION_NAMESPACE . '-app', 'yabeWebfont', [
            '_version' => Plugin::VERSION,
            '_wpnonce' => wp_create_nonce(YABE_WEBFONT_OPTION_NAMESPACE . '-app'),
            'web_history'  => add_query_arg([
                'page' => YABE_WEBFONT_OPTION_NAMESPACE,
            ], admin_url('admin.php')),
            // 'postUrl' => admin_url('post.php'),
            // 'mimeTypes' => array_keys(Font::get_custom_fonts_mime_types()),
            // 'hostedWakufont' => Font::hosted_wakufont(),
        ]);
    }
}
