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

namespace Yabe\Webfont\Utils;

/**
 * Common utility functions for the plugin.
 *
 * @author Joshua <joshua@rosua.org>
 */
class Common
{
    /**
     * Check the type of the current request.
     *
     * @param string $type The type of the request. Available values: `admin`, `ajax`, `frontend`, `rest`, `cron`.
     */
    public static function is_request(string $type): bool
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'rest':
                return defined('REST_REQUEST');
            case 'cron':
                return defined('DOING_CRON');
            case 'frontend':
                return (! is_admin() || defined('DOING_AJAX')) && ! defined('DOING_CRON');
            default:
                return false;
        }
    }

    /**
     * Get the plugin's data.
     *
     * @param string $key The key to retrieve.
     * @return mixed
     */
    public static function plugin_data(?string $key = null)
    {
        if (! function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $plugin_data = wp_cache_get('plugin_data', YABE_WEBFONT_OPTION_NAMESPACE);

        if (! $plugin_data) {
            $plugin_data = get_plugin_data(YABE_WEBFONT_FILE);
            wp_cache_set('plugin_data', $plugin_data, YABE_WEBFONT_OPTION_NAMESPACE);
        }

        return $key ? $plugin_data[$key] : $plugin_data;
    }
}
