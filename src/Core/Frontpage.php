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

/**
 * Serve the font on the frontpage.
 *
 * @author Joshua <id@rosua.org>
 */
final class Frontpage
{
    public function __construct()
    {
        add_action('wp_head', fn () => $this->preload(), 1_000_001);
        add_action('wp_head', static fn () => self::enqueue_css_cache(), 1_000_001);
    }

    public static function enqueue_css_cache()
    {
        if (defined('YABE_WEBFONT_CSS_CACHE_WAS_LOADED')) {
            return;
        }

        if (file_exists(Cache::get_cache_path(Cache::CSS_CACHE_FILE))) {
            $handle = YABE_WEBFONT_OPTION_NAMESPACE . '-cache';
            $version = (string) filemtime(Cache::get_cache_path(Cache::CSS_CACHE_FILE));
            wp_register_style($handle, Cache::get_cache_url(Cache::CSS_CACHE_FILE), [], $version);
            do_action('a!yabe/webfont/core/frontpage:before_print_style');
            wp_print_styles($handle);
        }

        define('YABE_WEBFONT_CSS_CACHE_WAS_LOADED', true);
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
            if ($preload_html !== false) {
                echo $preload_html;
            }
        }

        define('YABE_WEBFONT_PRELOAD_HTML_WAS_LOADED', true);
    }
}
