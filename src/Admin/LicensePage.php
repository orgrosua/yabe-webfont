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

use EDD_SL\PluginUpdater;
use Yabe\Webfont\Plugin;
use Yabe\Webfont\Utils\Notice;

/**
 * The preview and import page for the webfont.
 *
 * @author Joshua <joshua@rosua.org>
 */
class LicensePage
{
    private PluginUpdater $plugin_updater;

    public function __construct()
    {
        add_filter('plugin_action_links_' . plugin_basename(YABE_WEBFONT_FILE), fn ($links) => $this->plugin_action_links($links));

        add_action('admin_menu', fn () => $this->admin_menu(), 100);

        $this->maybe_update_plugin();
    }

    /**
     * Initialize the plugin updater.
     */
    public function maybe_update_plugin()
    {
        $license_key = get_option(YABE_WEBFONT_OPTION_NAMESPACE . '_license_key');
        $opt_in_beta = get_option(YABE_WEBFONT_OPTION_NAMESPACE . '_opt_in_beta');

        $this->plugin_updater = new PluginUpdater(
            YABE_WEBFONT_OPTION_NAMESPACE,
            [
                'version' => Plugin::VERSION,
                'license' => $license_key ? trim($license_key) : false,
                'beta' => $opt_in_beta,
                'plugin_file' => YABE_WEBFONT_FILE,
                'item_id' => YABE_WEBFONT_EDD_STORE['item_id'],
                'store_url' => YABE_WEBFONT_EDD_STORE['url'],
                'author' => YABE_WEBFONT_EDD_STORE['author'],
            ]
        );
    }

    /**
     * Add action link to the plugin management page.
     *
     * @param array $links The current action links.
     * @return array The modified action links.
     */
    public function plugin_action_links($links)
    {
        $plugin_shortcuts = [
            sprintf('<a href="%s">%s</a>', add_query_arg([
                'page' => YABE_WEBFONT_OPTION_NAMESPACE . '-license',
            ], admin_url('admin.php')), __('License', 'yabe-webfont')),
        ];

        return array_merge($links, $plugin_shortcuts);
    }

    public function admin_menu()
    {
        $hook = add_submenu_page(
            null,
            __('Yabe Webfont License', 'yabe-webfont'),
            __('Yabe Webfont License', 'yabe-webfont'),
            'manage_options',
            YABE_WEBFONT_OPTION_NAMESPACE . '-license',
            fn () => $this->render()
        );

        add_action('load-' . $hook, fn () => $this->init_hooks());
    }

    public function init_hooks()
    {
    }

    public function render()
    {
        $wp_nonce_field = wp_nonce_field(YABE_WEBFONT_OPTION_NAMESPACE, '_wpnonce', true, false);

        $license_key = get_option(YABE_WEBFONT_OPTION_NAMESPACE . '_license_key');

        if (isset($_POST['submit'])) {
            if (! wp_verify_nonce($_POST['_wpnonce'], YABE_WEBFONT_OPTION_NAMESPACE)) {
                Notice::error('Nonce verification failed');

                echo sprintf('<script>window.location.href = "%s";</script>', add_query_arg([], false));
                exit;
            }

            $req_license_key = sanitize_text_field($_REQUEST['license_key']);

            if ($req_license_key !== get_option(YABE_WEBFONT_OPTION_NAMESPACE . '_license_key')) {
                if (empty($req_license_key)) {
                    $this->plugin_updater->deactivate();
                    update_option(YABE_WEBFONT_OPTION_NAMESPACE . '_license_key', null);

                    Notice::success('Plugin license key de-activated successfully');
                } else {
                    $response = $this->plugin_updater->activate($req_license_key);

                    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
                        Notice::error(is_wp_error($response) ? $response->get_error_message() : 'An error occurred, please try again.');
                    } else {
                        try {
                            $license_data = json_decode(wp_remote_retrieve_body($response), null, 512, JSON_THROW_ON_ERROR);

                            if ($license_data->license !== 'valid') {
                                Notice::error($this->plugin_updater->error_message($license_data->error));
                            } else {
                                update_option(YABE_WEBFONT_OPTION_NAMESPACE . '_license_key', $req_license_key);
                                Notice::success('Plugin license key activated successfully');
                            }
                        } catch (\Throwable) {
                            Notice::error('An error occurred while parsing the response, please try again.');
                        }
                    }
                }
            }

            Notice::success('Change have been saved!');
            echo sprintf('<script>window.location.href = "%s";</script>', add_query_arg([], false));

            exit;
        }

        $is_license_activated = false;

        try {
            $is_license_activated = $this->plugin_updater->is_activated();
        } catch (\Throwable) {
        }
        ?>
        <div class="wrap bricks-admin-wrapper license">
            <h1 class="title">Yabe Webfont — <?= __('License', 'yabe-webfont') ?></h1>

            <div class="bricks-admin-inner">
                <form id="yabe-webfont-license-key-form" method="post">
                    <?= $wp_nonce_field ?>
                    <p>Enter your <a href="https://l.suabahasa.dev/fs0UR" target="_blank">license key</a> to receive the update of the latest version.</p>
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="password" name="license_key" value="<?= $license_key ?>">
                        </div>
                        <input type="submit" name="submit" id="submit" class="button button-secondary button-large">
                    </div>

                    <?php if ($is_license_activated) : ?>
                        <div class="status-wrapper">
                            Status: <span class="status processed">Active</span>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
<?php
    }
}
