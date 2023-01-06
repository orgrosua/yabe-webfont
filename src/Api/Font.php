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

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use wpdb;
use Yabe\Webfont\Utils\Common;

use function current_user_can;
use function sanitize_text_field;
use function wp_get_attachment_url;
use function wp_verify_nonce;

class Font extends AbstractApi implements ApiInterface
{
    public function get_prefix(): string
    {
        return 'fonts';
    }

    public function register_custom_endpoints(): void
    {
        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/index',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'index'],
                'permission_callback' => [$this, 'permission_callback'],
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/store',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'store'],
                'permission_callback' => [$this, 'permission_callback'],
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/show/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'show'],
                'permission_callback' => [$this, 'permission_callback'],
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/update-status/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_status'],
                'permission_callback' => [$this, 'permission_callback'],
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/delete/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => [$this, 'destroy'],
                'permission_callback' => [$this, 'permission_callback'],
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/restore/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'restore'],
                'permission_callback' => [$this, 'permission_callback'],
            ]
        );
    }

    public function permission_callback(WP_REST_Request $request): bool
    {
        return wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest') && current_user_can('manage_options');
    }

    private function attach_font_files(array $font_faces): array
    {
        foreach ($font_faces as $i => $font_face) {
            foreach ($font_face->files as $j => $file) {
                $font_faces[$i]->files[$j]->attachment_url = wp_get_attachment_url($file->attachment_id);
            }
        }

        return $font_faces;
    }

    public function index(WP_REST_Request $request): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $soft_deleted = $request->get_param('soft_deleted') ? (bool) sanitize_text_field($request->get_param('soft_deleted')) : false;

        $page = $request->get_param('page') ? (int) sanitize_text_field($request->get_param('page')) : 1;
        $per_page = $request->get_param('per_page') ? (int) sanitize_text_field($request->get_param('per_page')) : 20;
        $offset = ($page * $per_page) - $per_page;

        $search = $request->get_param('search') ? sanitize_text_field($request->get_param('search')) : null;

        $items = [];

        $where_clause = [];

        if ($search) {
            $escaped_search = '%' . $wpdb->esc_like($search) . '%';
            $where_clause[] = $wpdb->prepare("( title LIKE '%1\$s' OR family LIKE '%1\$s' )", $escaped_search);
        }

        $where_clause[] = $soft_deleted ? 'deleted_at IS NOT NULL' : 'deleted_at IS NULL';

        $where_clause = count($where_clause) > 0 ? 'WHERE ' . implode(' AND ', $where_clause) : '';

        $sql = "
            SELECT * FROM {$wpdb->prefix}yabe_webfont_fonts
            {$where_clause}
            LIMIT {$per_page} OFFSET {$offset}
        ";

        $result = $wpdb->get_results($sql);

        foreach ($result as $row) {
            $items[] = [
                'id' => $row->id,
                'type' => $row->type,
                'title' => $row->title,
                'slug' => $row->slug,
                'family' => $row->family,
                'metadata' => json_decode($row->metadata),
                'font_faces' => json_decode($row->font_faces),
                'font_faces' => $this->attach_font_files(json_decode($row->font_faces)),
                'status' => (bool) $row->status,
                'created_at' => strtotime($row->created_at),
                'updated_at' => strtotime($row->updated_at),
                'deleted_at' => $row->deleted_at ? strtotime($row->deleted_at) : null,
            ];
        }

        $total_exists = (int) $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE deleted_at IS NULL
        ");

        $total_deleted = (int) $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE deleted_at IS NOT NULL
        ");

        $total_filtered =  (int) $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
            {$where_clause}
        ");

        $total_pages = ceil($total_filtered / $per_page);
        $from = count($items) > 0 ? ($page - 1) * $per_page + 1 : null;
        $to = count($items) > 0 ? $from + count($items) - 1 : null;

        return new WP_REST_Response([
            'data' => $items,
            'meta' => [
                'page' => $page,
                'per_page' => $per_page,
                'search' => $search,
                'total_pages' => $total_pages,
                'from' => $from,
                'to' => $to,
                'total_filtered' => $total_filtered,
                'total_deleted' => $total_deleted,
                'total_exists' => $total_exists,
            ],
        ], 200, [
            'X-WP-Total' => $total_filtered,
            'X-WP-TotalPages' => $total_pages,
        ]);
    }

    public function store(WP_REST_Request $request): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $payload = $request->get_json_params();

        $type = 'custom';
        $title = sanitize_text_field($payload['title']);
        $slug = Common::random_slug(10);
        $family = sanitize_text_field($payload['family']);
        $status = (bool) $payload['status'];
        $metadata = [
            'selector' => $payload['selector'],
            'display' => sanitize_text_field($payload['display']),
            'preload' => (bool) $payload['preload'],
        ];
        $font_faces = $payload['font_faces'];

        if (array_key_exists('metadata', $payload)) {
            $metadata = array_merge($metadata, $payload['metadata']);
        }

        $sql = "
            INSERT INTO {$wpdb->prefix}yabe_webfont_fonts
            (type, title, slug, family, status, metadata, font_faces, created_at)
            VALUES
            (%s, %s, %s, %s, %d, %s, %s, %s)
        ";

        $sql = $wpdb->prepare($sql, $type, $title, $slug, $family, $status, json_encode($metadata), json_encode($font_faces), current_time('mysql'));

        $wpdb->query($sql);

        $id = $wpdb->insert_id;

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    public function update_status(WP_REST_Request $request): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $request->get_url_params();
        $payload = $request->get_json_params();

        $id = (int) $url_params['id'];
        $status = (bool) $payload['status'];

        $sql = "
            SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $id);

        $count = (int) $wpdb->get_var($sql);

        if ($count === 0) {
            return new WP_REST_Response([
                'message' => __('Font not found', 'yabe-webfont'),
            ], 404, []);
        }

        $sql = "
            UPDATE {$wpdb->prefix}yabe_webfont_fonts
            SET status = %d
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $status, $id);

        $wpdb->query($sql);

        return new WP_REST_Response([
            'id' => $id,
            'status' => $status,
        ], 200, []);
    }

    public function destroy(WP_REST_Request $request): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $request->get_url_params();

        $id = (int) $url_params['id'];

        $sql = "
            SELECT deleted_at FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $id);

        $item = $wpdb->get_row($sql);

        if (!$item) {
            return new WP_REST_Response([
                'message' => __('Font not found', 'yabe-webfont'),
            ], 404, []);
        }

        if ($item->deleted_at) {
            $sql = "
                DELETE FROM {$wpdb->prefix}yabe_webfont_fonts
                WHERE id = %d
            ";
            $sql = $wpdb->prepare($sql, $id);
        } else {
            $sql = "
                UPDATE {$wpdb->prefix}yabe_webfont_fonts
                SET deleted_at = %s
                WHERE id = %d
            ";
            $sql = $wpdb->prepare($sql, current_time('mysql'), $id);
        }

        $wpdb->query($sql);

        return new WP_REST_Response(null, 200, []);
    }

    public function restore(WP_REST_Request $request): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $request->get_url_params();

        $id = (int) $url_params['id'];

        $sql = "
            SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $id);

        $count = (int) $wpdb->get_var($sql);

        if ($count === 0) {
            return new WP_REST_Response([
                'message' => __('Font not found', 'yabe-webfont'),
            ], 404, []);
        }

        $sql = "
            UPDATE {$wpdb->prefix}yabe_webfont_fonts
            SET deleted_at = null
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $id);

        $wpdb->query($sql);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }
}
