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

namespace Yabe\Webfont\Core;

use Yabe\Webfont\Core\Cache;
use Yabe\Webfont\Utils\Common;

/**
 * Serve the font on the frontpage.
 *
 * @author Joshua <joshua@rosua.org>
 */
final class Frontpage
{
    public function __construct()
    {
        add_action('wp_head', fn () => $this->preload(), 1000001);
        add_action('wp_head', fn () => self::enqueue_css_cache(), 1000001);
    }

    /**
     * Preload the fonts file on the frontpage.
     */
    private function preload()
    {
        if (defined('YABE_WEBFONT_PRELOAD_HTML_WAS_LOADED')) {
            return;
        }

        if (file_exists(Cache::get_cache_path(Cache::PRELOAD_HTML_FILE))) {
            $preload_html = file_get_contents(Cache::get_cache_path(Cache::PRELOAD_HTML_FILE));
            if (false !== $preload_html) {
                echo $preload_html;
            }
        }

        define('YABE_WEBFONT_PRELOAD_HTML_WAS_LOADED', true);
    }

    public static function enqueue_css_cache()
    {
        if (defined('YABE_WEBFONT_CSS_CACHE_WAS_LOADED')) {
            return;
        }

        if (file_exists(Cache::get_cache_path(Cache::CSS_CACHE_FILE))) {
            $handle = YABE_WEBFONT_OPTION_NAMESPACE . '-cache';
            $version = (string) filemtime(Cache::get_cache_path(Cache::CSS_CACHE_FILE));
            // // if load as file
            wp_register_style($handle, Cache::get_cache_url(Cache::CSS_CACHE_FILE), [], $version);
            wp_print_styles($handle);
            // // else if load as inline
            // $css = file_get_contents(Cache::get_cache_path(Cache::CSS_CACHE_FILE));
            // if (false !== $css) {
            //     echo '<style id="'.$handle.'">' . $css . '</style>';
            // }
        }

        define('YABE_WEBFONT_CSS_CACHE_WAS_LOADED', true);
    }
}
