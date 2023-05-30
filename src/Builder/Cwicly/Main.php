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

namespace Yabe\Webfont\Builder\Cwicly;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Utils\Font;

/**
 * Cwicly integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('rest_request_before_callbacks', fn ($response, array $handler, \WP_REST_Request $wprestRequest) => $this->deregister_fonts($response, $handler, $wprestRequest), 1_000_001, 3);
        add_filter('rest_request_after_callbacks', fn ($response, array $handler, \WP_REST_Request $wprestRequest) => $this->register_fonts($response, $handler, $wprestRequest), 1_000_001, 3);

        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('cwicly'), 1_000_001);
    }

    public function get_name(): string
    {
        return 'cwicly';
    }

    /**
     * @see https://github.com/WordPress/wordpress-develop/blob/ea08277674413b9853aa19df6ecc23841de894b6/src/wp-includes/rest-api/class-wp-rest-server.php#L1197
     * @param \WP_REST_Response|\WP_HTTP_Response|\WP_Error|mixed $response
     * @param array $handler  Route handler used for the request.
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response|\WP_HTTP_Response|\WP_Error|mixed
     */
    public function register_fonts($response, $handler, $request)
    {
        if ($request->get_route() === '/cwicly/v1/editor_start' && is_array($response)) {
            $fonts = Font::get_fonts();

            if (is_bool($response['localactivefonts'])) {
                $response['localactivefonts'] = [];
            }

            foreach ($fonts as $font) {
                $key = sprintf('custom-yabe-%s', Font::slugify($font['family']));

                $f = [
                    'family' => $font['family'],
                    'fonts' => [],
                    'type' => 'custom',
                    'category' => 'Sans Serif',
                    'display' => 'swap',
                    'subsets' => [],
                    'axes' => [],
                ];

                $response['localfonts'][$key] = $f;

                $response['localactivefonts'][] = $key;
            }
        }

        return $response;
    }

    /**
     * @see https://github.com/WordPress/wordpress-develop/blob/ea08277674413b9853aa19df6ecc23841de894b6/src/wp-includes/rest-api/class-wp-rest-server.php#L1197
     * @param \WP_REST_Response|\WP_HTTP_Response|\WP_Error|mixed $response
     * @param array $handler  Route handler used for the request.
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response|\WP_HTTP_Response|\WP_Error|mixed
     */
    public function deregister_fonts($response, $handler, $request)
    {
        if ($request->get_route() === '/cwicly/v1/options') {
            if ($request->get_param('option') === 'cwicly_local_fonts') {
                $value = $request->get_param('value');

                // remove array element with key 'custom-yabe-'
                $value = array_filter($value, static fn ($k) => strncmp($k, 'custom-yabe-', strlen('custom-yabe-')) !== 0, ARRAY_FILTER_USE_KEY);

                $request->set_param('value', $value);
            }

            if ($request->get_param('option') === 'cwicly_local_active_fonts') {
                $value = $request->get_param('value');

                // remove array element with value 'custom-yabe-'
                $value = array_filter($value, static fn ($v) => strncmp($v, 'custom-yabe-', strlen('custom-yabe-')) !== 0);

                $request->set_param('value', $value);
            }
        }

        return $response;
    }
}
