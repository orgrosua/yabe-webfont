<?php

/**
 * Yabe Webfont
 *
 * @wordpress-plugin
 * Plugin Name:         Yabe Webfont
 * Plugin URI:          https://yabe.land/webfont
 * Description:         Self-host Google Fonts with seamless Bricks' Custom Fonts integration
 * Version:             2.0.0-DEV
 * Requires at least:   6.0
 * Requires PHP:        7.4
 * Author:              Rosua
 * Author URI:          https://rosua.org
 * Donate link:         https://github.com/sponsors/suabahasa
 * Text Domain:         yabe-webfont
 * Domain Path:         /languages
 *
 * @package             Yabe
 * @author              Joshua <joshua@rosua.org>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

define('YABE_WEBFONT_FILE', __FILE__);

/**
 * Namespace or prefix of the Package's on the wp_options table.
 */
define('YABE_WEBFONT_OPTION_NAMESPACE', 'yabe_webfont');

define('YABE_WEBFONT_HOSTED_WAKUFONT', 'https://wakufont-hosted.rosua.org');

define('YABE_WEBFONT_SENTRY_DSN', 'https://6decbda1f9474f9da6191044c02bcd30@sentry.suabahasa.dev/3');

define('YABE_WEBFONT_EDD_STORE', [
    'url' => 'https://rosua.org',
    'item_id' => 18,
    'author' => 'idrosua',
]);

require_once __DIR__ . '/vendor/autoload.php';

\Yabe\Webfont\Plugin::get_instance()->boot();
