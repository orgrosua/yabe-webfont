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

use Exception;

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
                return wp_doing_ajax();
            case 'rest':
                return defined('REST_REQUEST') && REST_REQUEST;
            case 'cron':
                return wp_doing_cron();
            case 'json':
                return wp_is_json_request();
            case 'xmlrpc':
                return defined('XMLRPC_REQUEST') && XMLRPC_REQUEST;
            case 'xml':
                return wp_is_xml_request();
            case 'frontend':
                return (! is_admin() || wp_doing_ajax()) && ! wp_doing_cron();
            default:
                return false;
        }
    }

    /**
     * Save the worker result into the file.
     * 
     * @param string $content The content of the file.
     * @param string $file_path The file path.
     * @throws Exception
     */
    public static function save_file($content, $file_path): void
    {
        if (!file_exists($file_path)) {
            wp_mkdir_p(dirname($file_path));
        }

        $result = file_put_contents($file_path, $content);

        if ($result === false) {
            throw new Exception('Failed to save to file.', 500);
        }

        // if write is successful continue
        $saved_content = file_get_contents($file_path);

        // if read is successful continue
        if ($saved_content === false) {
            throw new Exception('The saved file is not readable.', 500);
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

    public static function random_slug(int $length = 21): string
    {
        return (new \Hidehalo\Nanoid\Client())->generateId($length, \Hidehalo\Nanoid\Client::MODE_DYNAMIC);
    }
}
