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

namespace Yabe\Webfont\Core;

use Yabe\Webfont\Plugin;
use Yabe\Webfont\Utils\Common;
use Yabe\Webfont\Utils\Notice;

/**
 * Manage the cache of fonts for the frontpage.
 *
 * @author Joshua <id@rosua.org>
 */
class Cache
{
    /**
     * @var string
     */
    public const CSS_CACHE_FILE = 'fonts.css';

    /**
     * @var string
     */
    public const PRELOAD_HTML_FILE = 'preload.html';

    /**
     * @var string
     */
    public const CACHE_DIR = '/yabe-webfont/cache/';

    public function __construct()
    {
        add_filter('cron_schedules', fn ($schedules) => $this->filter_cron_schedules($schedules));

        add_action('a!yabe/webfont/core/cache:build_cache', fn () => $this->build_cache());

        // listen to fonts event for cache build (async/scheduled)
        add_action('a!yabe/webfont/api/font:fonts_event_async', fn () => $this->schedule_cache(), 10, 1);

        // listen to fonts event for cache build (sync)
        add_action('a!yabe/webfont/api/font:fonts_event', fn () => $this->build_cache(), 10, 1);

        // listen to theme switch for cache build (async/scheduled)
        add_action('switch_theme', fn () => $this->schedule_cache(), 1_000_001);

        // listen to Config change for cache build (async/scheduled)
        add_action('f!yabe/webfont/api/setting/option:after_store', fn () => $this->schedule_cache(), 10, 1);

        // listen to plugin upgrade for cache build (async/scheduled)
        add_action('a!yabe/webfont/plugins:upgrade_plugin_end', fn () => $this->schedule_cache(), 10, 1);
    }

    public function filter_cron_schedules($schedules)
    {
        $schedules['yabe_webfont_cache'] = [
            'interval' => MINUTE_IN_SECONDS,
            'display' => __('Every minute', 'yabe-webfont'),
        ];

        return $schedules;
    }

    public function schedule_cache()
    {
        if (! wp_next_scheduled('a!yabe/webfont/core/cache:build_cache')) {
            wp_schedule_single_event(time() + 10, 'a!yabe/webfont/core/cache:build_cache');
        }
    }

    public static function get_cache_path(string $file_path = ''): string
    {
        return wp_upload_dir()['basedir'] . self::CACHE_DIR . $file_path;
    }

    public static function get_cache_url(string $file_path = ''): string
    {
        return wp_upload_dir()['baseurl'] . self::CACHE_DIR . $file_path;
    }

    public function build_cache()
    {
        $css = Runtime::build_css();

        $payload = sprintf(
            "/*\n! %s v%s | %s\n*/\n\n%s",
            Common::plugin_data('Name'),
            Plugin::VERSION,
            date('Y-m-d H:i:s', time()),
            $css
        );

        try {
            Common::save_file($payload, self::get_cache_path(self::CSS_CACHE_FILE));
        } catch (\Throwable $throwable) {
            Notice::error(sprintf('Failed to build Fonts CSS cache: %s', $throwable->getMessage()));
        }

        $preload_html = Runtime::build_preload();

        try {
            Common::save_file($preload_html, self::get_cache_path(self::PRELOAD_HTML_FILE));
        } catch (\Throwable $throwable) {
            Notice::error(sprintf('Failed to build Fonts Preload HTML cache: %s', $throwable->getMessage()));
        }

        $this->purge_cache_plugin();
    }

    /**
     * Clear the cache from various cache plugins.
     */
    private function purge_cache_plugin()
    {
        /**
         * WP Rocket
         * @see https://docs.wp-rocket.me/article/92-rocketcleandomain
         */
        if (function_exists('rocket_clean_domain')) {
            \rocket_clean_domain();
        }

        /**
         * WP Super Cache
         * @see https://github.com/Automattic/wp-super-cache/blob/a0872032b1b3fc6847f490eadfabf74c12ad0135/wp-cache-phase2.php#L3013
         */
        if (function_exists('wp_cache_clear_cache')) {
            \wp_cache_clear_cache();
        }

        /**
         * W3 Total Cache
         * @see https://github.com/BoldGrid/w3-total-cache/blob/3a094493064ea60d727b3389dee813639860ef49/w3-total-cache-api.php#L259
         */
        if (function_exists('w3tc_flush_all')) {
            \w3tc_flush_all();
        }

        /**
         * WP Fastest Cache
         * @see https://www.wpfastestcache.com/tutorial/delete-the-cache-by-calling-the-function/
         */
        if (function_exists('wpfc_clear_all_cache')) {
            \wpfc_clear_all_cache(true);
        }

        /**
         * LiteSpeed Cache
         * @see https://docs.litespeedtech.com/lscache/lscwp/api/#purge-all-existing-caches
         */
        do_action('litespeed_purge_all');
    }
}
