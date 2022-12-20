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

namespace Yabe\Webfont\Api;

use WP_Query;
use WP_REST_Request;
use WP_REST_Response;
use wpdb;

class Font extends AbstractApi implements ApiInterface
{
    public function getPrefix(): string
    {
        return 'fonts';
    }

    public function register_custom_endpoints(): void
    {
        register_rest_route(
            self::API_NAMESPACE,
            $this->getPrefix() . '/index',
            [
                'methods' => 'GET',
                'callback' => [$this, 'index'],
                // 'permission_callback' => [$this, 'permission_callback'],
            ]
        );
    }

    public function index(WP_REST_Request $request): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $page = $request->get_param('page') ? (int) sanitize_text_field($request->get_param('page')) : 1;
        $per_page = $request->get_param('per_page') ? (int) sanitize_text_field($request->get_param('per_page')) : 10;
        $offset = ($page * $per_page) - $per_page;

        $search = $request->get_param('search') ? sanitize_text_field($request->get_param('search')) : null;

        $items = [];

        if ($search) {
            $escaped_search = '%' . $wpdb->esc_like($search) . '%';
            $sql = "
                SELECT * FROM {$wpdb->prefix}yabe_webfont_fonts
                WHERE name LIKE '%1\$s'
                    OR slug LIKE '%1\$s'
                    OR family LIKE '%1\$s'
                LIMIT {$per_page} OFFSET {$offset}
            ";

            $sql = $wpdb->prepare($sql, $escaped_search);
        } else {
            $sql = "
                SELECT * FROM {$wpdb->prefix}yabe_webfont_fonts
                LIMIT {$per_page} OFFSET {$offset}
            ";
        }

        // TODO: Map result to $items array
        $result = $wpdb->get_results($sql);

        // foreach ($result as $row) {
        //     $items[] = [
        //         'id' => $row->id,
        //         'name' => $row->name,
        //         'slug' => $row->slug,
        //         'family' => $row->family,
        //         'variants' => $row->variants,
        //         'subsets' => $row->subsets,
        //         'created_at' => $row->created_at,
        //         'updated_at' => $row->updated_at,
        //     ];
        // }


        if ($search) {
            $escaped_search = '%' . $wpdb->esc_like($search) . '%';
            $sql = "
                SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
                WHERE name LIKE '%1\$s'
                    OR slug LIKE '%1\$s'
                    OR family LIKE '%1\$s'
            ";

            $sql = $wpdb->prepare($sql, $escaped_search);
        } else {
            $sql = "
                SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
            ";
        }

        $total = (int) $wpdb->get_var($sql);

        $total_pages = ceil($total / $per_page);
        $from = count($items) > 0 ? ($page - 1) * $per_page + 1 : null;
        $to = count($items) > 0 ? $from + count($items) - 1 : null;

        return new WP_REST_Response([
            'data' => $items,
            'meta' => [
                'page' => $page,
                'per_page' => $per_page,
                'search' => $search,
                'total' => $total,
                'total_pages' => $total_pages,
                'from' => $from,
                'to' => $to,
            ],
        ], 200, []);
    }
}
