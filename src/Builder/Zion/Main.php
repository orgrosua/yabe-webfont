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

namespace Yabe\Webfont\Builder\Zion;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Frontpage;
use Yabe\Webfont\Core\Runtime;

/**
 * Zion integration.
 *
 * @author Joshua <joshua@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('rest_request_after_callbacks', fn ($response, array $handler, \WP_REST_Request $wprestRequest) => $this->filter_rest_request_after_callbacks($response, $handler, $wprestRequest), 100001, 3);
        add_action('zionbuilder/editor/after_scripts', fn () => $this->enqueue_assets());
    }

    public function get_name(): string
    {
        return 'zion';
    }

    /**
     * @see https://github.com/WordPress/wordpress-develop/blob/ea08277674413b9853aa19df6ecc23841de894b6/src/wp-includes/rest-api/class-wp-rest-server.php#L1197
     * @param \WP_REST_Response|\WP_HTTP_Response|\WP_Error|mixed $response
     * @param array $handler  Route handler used for the request.
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response|\WP_HTTP_Response|\WP_Error|mixed
     */
    public function filter_rest_request_after_callbacks($response, $handler, $request)
    {
        if ($request->get_route() !== '/zion/v1/data-sets' && $response instanceof \WP_REST_Response) {
            $data = $response->get_data();
            if (array_key_exists('fonts_list', $data)) {
                if (! array_key_exists('custom_fonts', $data['fonts_list'])) {
                    $data['fonts_list']['custom_fonts'] = [];
                }
                $font_families = Runtime::get_font_families();

                foreach ($font_families as $font_family) {
                    $data['fonts_list']['custom_fonts'][] = [
                        'id' => $font_family['family'],
                        'name' => '[Yabe] ' . $font_family['title'],
                    ];
                }

                $response->set_data($data);
            }
        }

        return $response;
    }

    public function enqueue_assets()
    {
        Frontpage::enqueue_css_cache();
    }
}
