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

class AdminPage
{
    public function __construct()
    {
        add_filter('wp_check_filetype_and_ext', static fn ($data, $file, $filename, $mimes) => Upload::disable_real_mime_check($data, $file, $filename, $mimes), 10, 4);
        add_filter('upload_mimes', static fn ($mime_types) => Upload::upload_mimes($mime_types), 10001);

        add_action('admin_menu', fn () => $this->add_admin_menu());
    }

    public function add_admin_menu()
    {
        $hook = add_theme_page(
            __('Yabe Webfont', 'yabe-webfont'),
            __('Yabe Webfont', 'yabe-webfont'),
            'manage_options',
            YABE_WEBFONT_OPTION_NAMESPACE,
            fn () => $this->render()
        );

        add_action('load-' . $hook, fn () => $this->init_hooks());
    }

    private function render()
    {
        add_filter('admin_footer_text', fn ($text) => $this->admin_footer_text($text), 10001);
        echo '<div id="yabe-webfont-app" class=""></div>';
    }

    private function init_hooks()
    {
        add_action('admin_enqueue_scripts', fn () => $this->enqueue_scripts());
    }

    private function enqueue_scripts()
    {
        wp_enqueue_media();

        wp_enqueue_style(YABE_WEBFONT_OPTION_NAMESPACE . '-app', plugin_dir_url(YABE_WEBFONT_FILE) . 'build/app.css', [], filemtime(plugin_dir_path(YABE_WEBFONT_FILE) . 'build/app.css'));
        wp_enqueue_script(YABE_WEBFONT_OPTION_NAMESPACE . '-app', plugin_dir_url(YABE_WEBFONT_FILE) . 'build/app.js', [], filemtime(plugin_dir_path(YABE_WEBFONT_FILE) . 'build/app.js'), true);

        wp_set_script_translations(YABE_WEBFONT_OPTION_NAMESPACE . '-app', 'yabe-webfont');
        wp_localize_script(YABE_WEBFONT_OPTION_NAMESPACE . '-app', 'yabeWebfont', [
            '_version' => Plugin::VERSION,
            '_wpnonce' => wp_create_nonce(YABE_WEBFONT_OPTION_NAMESPACE),
            'web_history' => add_query_arg([
                'page' => YABE_WEBFONT_OPTION_NAMESPACE,
            ], admin_url('admin.php')),
            'rest_api' => [
                'nonce' => wp_create_nonce('wp_rest'),
                'root' => esc_url_raw(rest_url()),
                'namespace' => YABE_WEBFONT_REST_NAMESPACE,
                'url' => esc_url_raw(rest_url(YABE_WEBFONT_REST_NAMESPACE)),
            ],
            'assets' => [
                'url' => plugin_dir_url(YABE_WEBFONT_FILE),
            ],
            'hostedWakufont' => rtrim(apply_filters('f!yabe/webfont/font:wakufont_self_hosted', defined('YABE_SELF_HOSTED_WAKUFONT') ? constant('YABE_SELF_HOSTED_WAKUFONT') : YABE_WEBFONT_HOSTED_WAKUFONT), '/'),
            // 'postUrl' => admin_url('post.php'),
        ]);
    }

    private function admin_footer_text($text): string
    {
        return sprintf(
            __('Thank you for using <b>Yabe Webfont</b>! Join us on the <a href="%s" target="_blank">Facebook Group</a>.', 'yabe-webfont'),
            'https://l.suabahasa.dev/YkV8t'
        );
    }
}
