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

namespace Yabe\Webfont\Utils;

use function Composer\Autoload\includeFile;

use function Sentry\configureScope as sentryConfigureScope;
use function Sentry\init as sentryInit;
use Sentry\SentrySdk;

/**
 * Monitors errors and exceptions.
 *
 * @author Joshua <joshua@rosua.org>
 */
class Diagnose
{
    public function __construct(string $release)
    {
        sentryInit([
            'dsn' => YABE_SENTRY_DSN,
            'release' => $release,
            'environment' => defined('WP_DEBUG') && WP_DEBUG ? 'development' : 'production',
            'attach_stacktrace' => true,
        ]);

        $this->register_tags();
        $this->register_context();
        $this->register_user();

        add_filter('wp_php_error_message', fn ($message, $error) => $this->user_feedback_popup($message, $error), 10, 2);
    }

    public function user_feedback_popup($message, $error)
    {
        $sentryLastEventId = SentrySdk::getCurrentHub()->getLastEventId();

        if ($sentryLastEventId !== null) {
            $message .= '<script src="https://browser.sentry-cdn.com/7.17.1/bundle.min.js" integrity="sha384-vNdCKj9jIX+c41215wXDL6Xap/hZNJ8oyy/om470NxVJHff8VAQck1xu53ZYZ7wI" crossorigin="anonymous"></script>';
            $message .= '<script>Sentry.init({ dsn: "' . YABE_SENTRY_DSN . '" });Sentry.showReportDialog({
                eventId: "' . $sentryLastEventId . '",
                title: "Yabe Webfont has detected an error",
                subtitle: "Our team has been notified. Please help us to improve our plugin by sending us a report.",
            });</script>';
        }
        return $message;
    }

    public function register_tags()
    {
        sentryConfigureScope(function (\Sentry\State\Scope $scope): void {
            $scope->setTag('wordpress.version', get_bloginfo('version'));
        });
    }

    public function register_user()
    {
        add_action('init', function (): void {
            sentryConfigureScope(function (\Sentry\State\Scope $scope): void {
                $user = wp_get_current_user();

                if ($user->exists()) {
                    $scope->setUser([
                        'username' => $user->user_login,
                        'email' => $user->user_email,
                        'roles' => $user->roles,
                        'capabilities' => $user->allcaps,
                    ]);
                }
            });
        });
    }

    public function register_context()
    {
        $plugins = $this->populateFieldPlugins();
        $themes = $this->populateFieldThemes();
        $database = $this->populateFieldDatabase();

        sentryConfigureScope(function (\Sentry\State\Scope $scope) use ($plugins, $themes, $database): void {
            $scope->setContext('wordpress', [
                'version' => get_bloginfo('version'),
                'multisite' => is_multisite(),
                'ssl' => is_ssl(),
                'plugins' => $plugins,
                'themes' => $themes,
                'server' => [
                    'server_architecture' => function_exists('php_uname') ? sprintf('%s %s %s', php_uname('s'), php_uname('r'), php_uname('m')) : null,
                    'httpd_software' => $_SERVER['SERVER_SOFTWARE'] ?? null,
                    'php_version' => PHP_VERSION . (PHP_INT_SIZE * 8 === 64 ? ' 64bit' : ''),
                    'php_sapi' => function_exists('php_sapi_name') ? PHP_SAPI : null,
                    'max_execution_time' => function_exists('ini_get') ? ini_get('max_execution_time') : null,
                    'memory_limit' => function_exists('ini_get') ? ini_get('memory_limit') : null,
                    'curl_version' => function_exists('curl_version') ? curl_version() : null,
                ],
                'database' => $database,
            ]);
        });
    }

    public function populateFieldDatabase(): array
    {
        global $wpdb;

        $database = [];

        if (is_resource($wpdb->dbh)) {
            $database['extension'] = 'mysql';
        } elseif (is_object($wpdb->dbh)) {
            $database['extension'] = $wpdb->dbh::class;
        } else {
            $database['extension'] = null;
        }

        if (property_exists($wpdb, 'use_mysqli') && $wpdb->use_mysqli !== null && $wpdb->use_mysqli) {
            $database['server_version'] = mysqli_get_server_info($wpdb->dbh);
            $database['client_version'] = $wpdb->dbh->client_info;
        } else {
            $database['server_version'] = $wpdb->get_var('SELECT VERSION()');
            $database['client_version'] = preg_match('|\d{1,2}\.\d{1,2}\.\d{1,2}|', mysql_get_client_info(), $matches) ? $matches[0] : null;
        }

        return $database;
    }

    public function populateFieldPlugins(): array
    {
        if (! function_exists('get_plugins')) {
            includeFile(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        if (! function_exists('get_plugin_updates')) {
            includeFile(ABSPATH . 'wp-admin/includes/update.php');
        }

        $plugin_updates = get_plugin_updates();
        $plugins = [
            'active' => [],
            'inactive' => [],
            'mu' => get_mu_plugins(),
        ];

        foreach (get_plugins() as $plugin_path => $plugin) {
            if (array_key_exists($plugin_path, $plugin_updates)) {
                $plugin['updates'] = $plugin_updates[$plugin_path]->update;
            }
            $plugins[is_plugin_active($plugin_path) ? 'active' : 'inactive'][] = $plugin;
        }

        return $plugins;
    }

    public function populateFieldThemes(): array
    {
        $info = [];
        $auto_updates_string = null;
        // Populate the section for the currently active theme.
        global $_wp_theme_features;
        $theme_features = [];

        if (! empty($_wp_theme_features)) {
            foreach ($_wp_theme_features as $feature => $options) {
                $theme_features[] = $feature;
            }
        }

        $active_theme = wp_get_theme();
        $theme_updates = get_theme_updates();
        $transient = get_site_transient('update_themes');

        $active_theme_version = $active_theme->version;

        $auto_updates = [];
        $auto_updates_enabled = wp_is_auto_update_enabled_for_type('theme');
        if ($auto_updates_enabled) {
            $auto_updates = (array) get_site_option('auto_update_themes', []);
        }

        if (array_key_exists($active_theme->stylesheet, $theme_updates)) {
            $theme_update_new_version = $theme_updates[$active_theme->stylesheet]->update['new_version'];

            /* translators: %s: Latest theme version number. */
            $active_theme_version .= ' ' . sprintf(__('(Latest version: %s)'), $theme_update_new_version);
        }

        if ($active_theme->parent_theme) {
            $active_theme_parent_theme = sprintf(
                /* translators: 1: Theme name. 2: Theme slug. */
                __('%1$s (%2$s)'),
                $active_theme->parent_theme,
                $active_theme->template
            );
        } else {
            $active_theme_parent_theme = __('None');
        }

        $info['active'] = [
            'name' => sprintf(
                /* translators: 1: Theme name. 2: Theme slug. */
                __('%1$s (%2$s)'),
                $active_theme->name,
                $active_theme->stylesheet
            ),
            'version' => $active_theme_version,
            'author' => wp_kses($active_theme->author, []),
            'author_website' => $active_theme->display('AuthorURI') ?: __('Undefined'),
            'parent_theme' => $active_theme_parent_theme,
            'theme_features' => implode(', ', $theme_features),
            'theme_path' => get_stylesheet_directory(),
        ];

        if ($auto_updates_enabled) {
            if (isset($transient->response[$active_theme->stylesheet])) {
                $item = $transient->response[$active_theme->stylesheet];
            } elseif (isset($transient->no_update[$active_theme->stylesheet])) {
                $item = $transient->no_update[$active_theme->stylesheet];
            } else {
                $item = [
                    'theme' => $active_theme->stylesheet,
                    'new_version' => $active_theme->version,
                    'url' => '',
                    'package' => '',
                    'requires' => '',
                    'requires_php' => '',
                ];
            }

            $auto_update_forced = wp_is_auto_update_forced_for_item('theme', null, (object) $item);

            $enabled = $auto_update_forced ?? in_array($active_theme->stylesheet, $auto_updates, true);

            $auto_updates_string = $enabled ? __('Enabled') : __('Disabled');

            /** This filter is documented in wp-admin/includes/class-wp-debug-data.php */
            $auto_updates_string = apply_filters('theme_auto_update_debug_string', $auto_updates_string, $active_theme, $enabled);

            $info['active']['auto_update'] = $auto_updates_string;
        }

        $parent_theme = $active_theme->parent();

        if ($parent_theme) {
            $parent_theme_version = $parent_theme->version;

            if (array_key_exists($parent_theme->stylesheet, $theme_updates)) {
                $parent_theme_update_new_version = $theme_updates[$parent_theme->stylesheet]->update['new_version'];

                /* translators: %s: Latest theme version number. */
                $parent_theme_version .= ' ' . sprintf(__('(Latest version: %s)'), $parent_theme_update_new_version);
            }

            $parent_theme_author_uri = $parent_theme->display('AuthorURI');

            $info['parent'] = [
                'name' => sprintf(
                    /* translators: 1: Theme name. 2: Theme slug. */
                    __('%1$s (%2$s)'),
                    $parent_theme->name,
                    $parent_theme->stylesheet
                ),
                'version' => $parent_theme_version,
                'author' => wp_kses($parent_theme->author, []),
                'author_website' => ($parent_theme_author_uri ?: __('Undefined')),
                'theme_path' => get_template_directory(),
            ];

            if ($auto_updates_enabled) {
                if (isset($transient->response[$parent_theme->stylesheet])) {
                    $item = $transient->response[$parent_theme->stylesheet];
                } elseif (isset($transient->no_update[$parent_theme->stylesheet])) {
                    $item = $transient->no_update[$parent_theme->stylesheet];
                } else {
                    $item = [
                        'theme' => $parent_theme->stylesheet,
                        'new_version' => $parent_theme->version,
                        'url' => '',
                        'package' => '',
                        'requires' => '',
                        'requires_php' => '',
                    ];
                }

                $auto_update_forced = wp_is_auto_update_forced_for_item('theme', null, (object) $item);

                $enabled = $auto_update_forced ?? in_array($parent_theme->stylesheet, $auto_updates, true);

                $parent_theme_auto_update_string = $enabled ? __('Enabled') : __('Disabled');

                /** This filter is documented in wp-admin/includes/class-wp-debug-data.php */
                $parent_theme_auto_update_string = apply_filters('theme_auto_update_debug_string', $auto_updates_string, $parent_theme, $enabled);

                $info['parent']['auto_update'] = [
                    'label' => __('Auto-update'),
                    'value' => $parent_theme_auto_update_string,
                    'debug' => $parent_theme_auto_update_string,
                ];
            }
        }

        // Populate a list of all themes available in the install.
        $all_themes = wp_get_themes();

        foreach ($all_themes as $theme_slug => $theme) {
            // Exclude the currently active theme from the list of all themes.
            if ($active_theme->stylesheet === $theme_slug) {
                continue;
            }

            // Exclude the currently active parent theme from the list of all themes.
            if (! empty($parent_theme) && $parent_theme->stylesheet === $theme_slug) {
                continue;
            }

            $theme_version = $theme->version;
            $theme_author = $theme->author;

            // Sanitize.
            $theme_author = wp_kses($theme_author, []);

            $theme_version_string = __('No version or author information is available.');

            if (! empty($theme_version) && ! empty($theme_author)) {
                /* translators: 1: Theme version number. 2: Theme author name. */
                $theme_version_string = sprintf(__('Version %1$s by %2$s'), $theme_version, $theme_author);
            } else {
                if (! empty($theme_author)) {
                    /* translators: %s: Theme author name. */
                    $theme_version_string = sprintf(__('By %s'), $theme_author);
                }

                if (! empty($theme_version)) {
                    /* translators: %s: Theme version number. */
                    $theme_version_string = sprintf(__('Version %s'), $theme_version);
                }
            }

            if (array_key_exists($theme_slug, $theme_updates)) {
                /* translators: %s: Latest theme version number. */
                $theme_version_string .= ' ' . sprintf(__('(Latest version: %s)'), $theme_updates[$theme_slug]->update['new_version']);
            }

            if ($auto_updates_enabled) {
                if (isset($transient->response[$theme_slug])) {
                    $item = $transient->response[$theme_slug];
                } elseif (isset($transient->no_update[$theme_slug])) {
                    $item = $transient->no_update[$theme_slug];
                } else {
                    $item = [
                        'theme' => $theme_slug,
                        'new_version' => $theme->version,
                        'url' => '',
                        'package' => '',
                        'requires' => '',
                        'requires_php' => '',
                    ];
                }

                $auto_update_forced = wp_is_auto_update_forced_for_item('theme', null, (object) $item);

                $enabled = $auto_update_forced ?? in_array($theme_slug, $auto_updates, true);

                $auto_updates_string = $enabled ? __('Auto-updates enabled') : __('Auto-updates disabled');

                /**
                 * Filters the text string of the auto-updates setting for each theme in the Site Health debug data.
                 *
                 * @since 5.5.0
                 *
                 * @param string   $auto_updates_string The string output for the auto-updates column.
                 * @param WP_Theme $theme               An object of theme data.
                 * @param bool     $enabled             Whether auto-updates are enabled for this item.
                 */
                $auto_updates_string = apply_filters('theme_auto_update_debug_string', $auto_updates_string, $theme, $enabled);

                $theme_version_string .= ' | ' . $auto_updates_string;
            }

            $info['inactive'][sanitize_text_field($theme->name)] = $theme_version_string;
        }

        return $info;
    }
}
