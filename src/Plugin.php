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

namespace Yabe\Webfont;

use Exception;
use Yabe\Webfont\Admin\ImportPage;
use Yabe\Webfont\Core\Font;
use Yabe\Webfont\Utils\Common;
use Yabe\Webfont\Utils\Diagnose;
use Yabe\Webfont\Utils\Notice;

/**
 * Manage the plugin lifecycle and provides a single point of entry to the plugin.
 *
 * @author Joshua <joshua@rosua.org>
 */
final class Plugin
{
    public const VERSION = '1.0.0-DEV';

    public const VERSION_ID = 10000;

    public const MAJOR_VERSION = 1;

    public const MINOR_VERSION = 0;

    public const RELEASE_VERSION = 0;

    public const EXTRA_VERSION = 'DEV';

    /**
     * Stores the instance, implementing a Singleton pattern.
     */
    private static self $instance;

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    private function __construct()
    {
    }

    /**
     * Singletons should not be cloneable.
     */
    private function __clone()
    {
    }

    /**
     * Singletons should not be restorable from strings.
     *
     * @throws Exception Cannot unserialize a singleton.
     */
    public function __wakeup()
    {
        throw new Exception('Cannot unserialize a singleton.');
    }

    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static property. On subsequent runs, it returns the client existing
     * object stored in the static property.
     */
    public static function get_instance(): self
    {
        $cls = static::class;
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function boot_sentry()
    {
        new Diagnose(self::VERSION);
    }

    /**
     * Entry to the Plugin.
     */
    public function boot(): void
    {
        do_action('a!yabe/webfont/plugins:boot_start');

        if (defined('YABE_WEBFONT_ERROR_REPORTING') && constant('YABE_WEBFONT_ERROR_REPORTING') === true) {
            $this->boot_sentry();
        }

        // (de)activation hooks.
        register_activation_hook(YABE_WEBFONT_FILE, function (): void {
            $this->activate_plugin();
        });
        register_deactivation_hook(YABE_WEBFONT_FILE, function (): void {
            $this->deactivate_plugin();
        });

        // admin hooks.
        if (is_admin()) {
            add_action('plugins_loaded', function (): void {
                $this->plugins_loaded();
            }, 100);

            do_action('a!yabe/webfont/plugins:boot_admin');

            new ImportPage();
            new Font();
        }

        do_action('a!yabe/webfont/plugins:boot_end');
    }

    /**
     * Handle the plugin's activation
     */
    public function activate_plugin(): void
    {
        do_action('a!yabe/webfont/plugins:activate_plugin_start');

        add_option(YABE_WEBFONT_OPTION_NAMESPACE . '_version', self::VERSION);

        do_action('a!yabe/webfont/plugins:activate_plugin_end');
    }

    /**
     * Handle plugin's deactivation by (maybe) cleaning up after ourselves.
     */
    public function deactivate_plugin(): void
    {
        do_action('a!yabe/webfont/plugins:deactivate_plugin_start');
        // TODO: Add deactivation logic here.
        do_action('a!yabe/webfont/plugins:deactivate_plugin_end');
    }

    /**
     * Warm up the plugin by registering core hooks.
     */
    public function plugins_loaded(): void
    {
        add_action('admin_notices', function () {
            $messages = Notice::get_lists();

            if ($messages && is_array($messages)) {
                foreach ($messages as $message) {
                    echo sprintf(
                        '<div class="notice notice-%s is-dismissible"><p><b>%s:</b> %s</p></div>',
                        $message['status'],
                        Common::plugin_data('Name'),
                        $message['message']
                    );
                }
            }
        }, 100);
    }
}
