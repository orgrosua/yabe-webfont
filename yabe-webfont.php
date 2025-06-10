<?php

/**
 * Yabe Webfont
 *
 * @wordpress-plugin
 * Plugin Name:         Yabe Webfont
 * Plugin URI:          https://webfont.yabe.land
 * Description:         Easy self-host Google Fonts, Adobe Fonts support, or upload custom fonts in WordPress that are integrated into the most popular themes and page builders.
 * Version:             2.0.74
 * Requires at least:   6.0
 * Requires PHP:        7.4
 * Author:              Rosua
 * Author URI:          https://rosua.org
 * Donate link:         https://ko-fi.com/Q5Q75XSF7
 * Text Domain:         yabe-webfont
 * Domain Path:         /languages
 * License:             GPL-3.0-or-later
 *
 * @package             Yabe
 * @author              Joshua Gugun Siagian <suabahasa@gmail.com>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

if (file_exists(__DIR__ . '/vendor/scoper-autoload.php')) {
    require_once __DIR__ . '/vendor/scoper-autoload.php';
} else {
    require_once __DIR__ . '/vendor/autoload.php';
}

\Yabe\Webfont\Plugin::get_instance()->boot();
