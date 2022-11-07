<?php

declare(strict_types=1);

/**
 * Yabe Webfont
 *
 * @wordpress-plugin
 * Plugin Name:         Yabe Webfont
 * Plugin URI:          https://yabe.land/webfont
 * Description:         Self-hosting Google Fonts and register to Bricks' Custom Font seamlessly
 * Version:             1.0.0-DEV
 * Requires at least:   6.0
 * Requires PHP:        8.0
 * Author:              Rosua
 * Author URI:          https://rosua.org
 * Donate link:         https://github.com/sponsors/suabahasa
 * Text Domain:         yabe-webfont
 * Domain Path:         /languages
 *
 * @package             Yabe
 * @author              Joshua <joshua@rosua.org>
 */

defined('ABSPATH') || exit;

define('YABE_WEBFONT_FILE', __FILE__);

/**
 * Namespace or prefix of the Package's on the wp_options table.
 */
define('YABE_WEBFONT_OPTION_NAMESPACE', 'yabe_webfont');

define('YABE_HOSTED_WAKUFONT', 'https://wakufont-hosted.rosua.org');

define('YABE_SENTRY_DSN', 'https://6decbda1f9474f9da6191044c02bcd30@sentry.suabahasa.dev/3');

require_once __DIR__ . '/vendor/autoload.php';

\Yabe\Webfont\Plugin::get_instance()->boot();
