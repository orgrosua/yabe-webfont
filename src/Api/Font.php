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

namespace Yabe\Webfont\Api;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use wpdb;
use Yabe\Webfont\Core\Runtime;
use Yabe\Webfont\Utils\Common;
use Yabe\Webfont\Utils\Upload;

class Font extends AbstractApi implements ApiInterface
{
    public function __construct()
    {
        $hooks = [
            'a!yabe/webfont/api/font:custom_store',
            'a!yabe/webfont/api/font:update_status',
            'a!yabe/webfont/api/font:destroy',
            'a!yabe/webfont/api/font:restore',
            'a!yabe/webfont/api/font:custom_update',
            'a!yabe/webfont/api/font:google_fonts_store',
            'a!yabe/webfont/api/font:google_fonts_update',
        ];
        foreach ($hooks as $hook) {
            add_action($hook, static function ($f) use ($hook) {
                /**
                 * Listen to several font events and emit a wrapper event
                 *
                 * @param string $hook Hook name
                 * @param object|int $f Font ID or Font Object
                 */
                do_action('a!yabe/webfont/api/font:fonts_event', $hook, $f);
            }, 10, 1);
        }
    }

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
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->index($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/custom/store',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->custom_store($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/update-status/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->update_status($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
                'args' => [
                    'status' => [
                        'required' => true,
                        'validate_callback' => static fn ($param): bool => is_bool($param),
                    ],
                ],
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/delete/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->destroy($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/restore/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->restore($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/detail/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->detail($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/custom/update/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->custom_update($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/store',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->google_fonts_store($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/update/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn (\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->google_fonts_update($wprestRequest),
                'permission_callback' => fn (\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );
    }

    private function permission_callback(WP_REST_Request $wprestRequest): bool
    {
        return wp_verify_nonce($wprestRequest->get_header('X-WP-Nonce'), 'wp_rest') && current_user_can('manage_options');
    }

    private function index(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $soft_deleted = $wprestRequest->get_param('soft_deleted') ? (bool) sanitize_text_field($wprestRequest->get_param('soft_deleted')) : false;

        $page = $wprestRequest->get_param('page') ? (int) sanitize_text_field($wprestRequest->get_param('page')) : 1;
        $per_page = $wprestRequest->get_param('per_page') ? (int) sanitize_text_field($wprestRequest->get_param('per_page')) : 20;
        $offset = ($page * $per_page) - $per_page;

        $search = $wprestRequest->get_param('search') ? sanitize_text_field($wprestRequest->get_param('search')) : null;

        $items = [];

        $where_clause = [];

        if ($search) {
            $escaped_search = '%' . $wpdb->esc_like($search) . '%';
            $where_clause[] = $wpdb->prepare("( title LIKE '%1\$s' OR family LIKE '%1\$s' )", $escaped_search);
        }

        $where_clause[] = $soft_deleted ? 'deleted_at IS NOT NULL' : 'deleted_at IS NULL';

        $where_clause = $where_clause !== [] ? 'WHERE ' . implode(' AND ', $where_clause) : '';

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
                'metadata' => json_decode($row->metadata, null, 512, JSON_THROW_ON_ERROR),
                'font_faces' => Runtime::refresh_font_faces_attachment_url(json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR)),
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

        $total_filtered = (int) $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->prefix}yabe_webfont_fonts
            {$where_clause}
        ");

        $total_pages = ceil($total_filtered / $per_page);
        $from = $items !== [] ? ($page - 1) * $per_page + 1 : null;
        $to = $items !== [] ? $from + count($items) - 1 : null;

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

    private function detail(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $wprestRequest->get_url_params();

        $id = (int) $url_params['id'];

        $sql = "
            SELECT * FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $id);

        $row = $wpdb->get_row($sql);

        if (! $row) {
            return new WP_REST_Response([
                'message' => 'Font not found',
            ], 404, []);
        }

        return new WP_REST_Response([
            'id' => $row->id,
            'type' => $row->type,
            'title' => $row->title,
            'slug' => $row->slug,
            'family' => $row->family,
            'metadata' => json_decode($row->metadata, null, 512, JSON_THROW_ON_ERROR),
            'font_faces' => Runtime::refresh_font_faces_attachment_url(json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR)),
            'status' => (bool) $row->status,
            'created_at' => strtotime($row->created_at),
            'updated_at' => strtotime($row->updated_at),
            'deleted_at' => $row->deleted_at ? strtotime($row->deleted_at) : null,
        ], 200, []);
    }

    private function custom_store(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $payload = $wprestRequest->get_json_params();

        $type = 'custom';
        $title = sanitize_text_field($payload['title']);
        $slug = Common::random_slug(10);
        $family = sanitize_text_field($payload['family']);
        $status = (bool) $payload['status'];
        $metadata = $payload['metadata'];
        $font_faces = $payload['font_faces'];

        $sql = "
            INSERT INTO {$wpdb->prefix}yabe_webfont_fonts
            (type, title, slug, family, status, metadata, font_faces)
            VALUES
            (%s, %s, %s, %s, %d, %s, %s)
        ";

        $sql = $wpdb->prepare($sql, $type, $title, $slug, $family, $status, json_encode($metadata, JSON_THROW_ON_ERROR), json_encode($font_faces, JSON_THROW_ON_ERROR));

        $wpdb->query($sql);

        $id = $wpdb->insert_id;

        do_action('a!yabe/webfont/api/font:custom_store', $id);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    private function update_status(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $wprestRequest->get_url_params();
        $payload = $wprestRequest->get_json_params();

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

        do_action('a!yabe/webfont/api/font:update_status', $id);

        return new WP_REST_Response([
            'id' => $id,
            'status' => $status,
        ], 200, []);
    }

    private function destroy(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $wprestRequest->get_url_params();

        $id = (int) $url_params['id'];

        $sql = "
            SELECT deleted_at FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $id);

        $item = $wpdb->get_row($sql);

        if (! $item) {
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

        do_action('a!yabe/webfont/api/font:destroy', $item);

        return new WP_REST_Response(null, 200, []);
    }

    private function restore(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $wprestRequest->get_url_params();

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

        do_action('a!yabe/webfont/api/font:restore', $id);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    private function custom_update(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $wprestRequest->get_url_params();
        $payload = $wprestRequest->get_json_params();

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

        $title = sanitize_text_field($payload['title']);
        $family = sanitize_text_field($payload['family']);
        $status = (bool) $payload['status'];
        $metadata = $payload['metadata'];
        $font_faces = $payload['font_faces'];

        $sql = "
            UPDATE {$wpdb->prefix}yabe_webfont_fonts
            SET title = %s,
                family = %s,
                status = %d,
                metadata = %s,
                font_faces = %s
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $title, $family, $status, json_encode($metadata, JSON_THROW_ON_ERROR), json_encode($font_faces, JSON_THROW_ON_ERROR), $id);

        $wpdb->query($sql);

        do_action('a!yabe/webfont/api/font:custom_update', $id);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    private function google_fonts_store(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $payload = $wprestRequest->get_json_params();

        $type = 'google-fonts';
        $title = sanitize_text_field($payload['title']);
        $slug = Common::random_slug(10);
        $status = (bool) $payload['status'];
        $metadata = $payload['metadata'];
        $family = $metadata['google_fonts']['font_data']['family'];
        $font_faces = [];

        add_filter('wp_check_filetype_and_ext', static fn ($data, $file, $filename, $mimes) => Upload::disable_real_mime_check($data, $file, $filename, $mimes), 10, 4);
        add_filter('upload_mimes', static fn ($mime_types) => Upload::upload_mimes($mime_types, true), 10002);

        $font_mime_types = [
            'woff2' => 'font/woff2',
            'woff' => 'font/woff',
            'ttf' => 'font/ttf',
        ];

        $m_font_faces = $metadata['google_fonts']['font_faces'];
        $m_font_files = $metadata['google_fonts']['font_files'];

        foreach ($m_font_faces as $k => $m_face) {
            if (! $m_face['isEnabled']) {
                continue;
            }

            if ($metadata['google_fonts']['variable']) {
                if ($m_face['weight'] !== 0) {
                    continue;
                }

                foreach ($metadata['google_fonts']['subsets'] as $subset) {
                    $filtered_m_font_files = array_filter(
                        $m_font_files,
                        static fn ($f) => $f['weight'] === $m_face['weight']
                            && $f['style'] === $m_face['style']
                            && in_array($subset, $f['subsets'], true)
                            && in_array($f['format'], $metadata['google_fonts']['formats'], true)
                    );

                    foreach ($filtered_m_font_files as $filtered_m_font_file) {
                        $wght = array_filter(
                            $metadata['google_fonts']['font_data']['axes'],
                            static fn ($a) => $a['tag'] === 'wght'
                        );

                        $wdth = array_filter(
                            $metadata['google_fonts']['font_data']['axes'],
                            static fn ($a) => $a['tag'] === 'wdth'
                        );

                        $wdth = array_values($wdth);
                        $wght = array_values($wght);

                        $font_face = [
                            'id' => Common::random_slug(10),
                            'weight' => $wght !== [] ? sprintf('%s %s', $wght[0]['min'], $wght[0]['max']) : '400',
                            'width' => $wdth !== [] ? sprintf('%s%% %s%%', $wdth[0]['min'], $wdth[0]['max']) : '100%',
                            'style' => $m_face['style'],
                            'display' => $m_face['display'],
                            'selector' => $m_face['selector'],
                            'comment' => $m_face['comment'],
                        ];

                        $file_name = sanitize_title_with_dashes(sprintf(
                            'google-fonts-%s-%s-%s-%s-%s-%s',
                            $metadata['google_fonts']['font_data']['slug'], // family
                            $metadata['google_fonts']['font_data']['version'],
                            $subset,
                            $filtered_m_font_file['weight'],
                            $filtered_m_font_file['style'],
                            time()
                        )) . '.' . $filtered_m_font_file['format'];

                        try {
                            $attachment_id = Upload::remote_upload_media($filtered_m_font_file['url'], $file_name, $font_mime_types[$filtered_m_font_file['format']]);

                            if (! $attachment_id) {
                                continue;
                            }
                        } catch (\Throwable $throwable) {
                            //throw $th;
                            continue;
                        }

                        $file = [
                            'uid' => Common::random_slug(10),
                            'attachment_id' => $attachment_id,
                            'attachment_url' => wp_get_attachment_url($attachment_id),
                            'extension' => $filtered_m_font_file['format'],
                            'mime' => $font_mime_types[$filtered_m_font_file['format']],
                            'file_size' => filesize(get_attached_file($attachment_id)),
                            'name' => substr($file_name, 0, strrpos($file_name, '.')),
                        ];

                        $metadata['google_fonts']['font_files'] = array_map(
                            static fn ($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
                                'file' => $file,
                            ]) : $f,
                            $metadata['google_fonts']['font_files']
                        );

                        $metadata['google_fonts']['font_faces'][$k]['attached_font_files'][] = $filtered_m_font_file['uid'];

                        $font_face['files'] = [$file];

                        $font_face['unicodeRange'] = $filtered_m_font_file['unicodeRange'];

                        $font_faces[] = $font_face;
                    }
                }
            } else {
                if ($m_face['weight'] === 0) {
                    continue;
                }

                $font_face = [
                    'id' => Common::random_slug(10),
                    'weight' => $m_face['weight'],
                    'width' => $m_face['width'] ?: '100%',
                    'style' => $m_face['style'],
                    'display' => $m_face['display'],
                    'selector' => $m_face['selector'],
                    'comment' => $m_face['comment'],
                    'unicodeRange' => '',
                ];

                $files = [];

                $filtered_m_font_files = array_filter(
                    $m_font_files,
                    static fn ($f) => $f['weight'] === $m_face['weight']
                        && $f['style'] === $m_face['style']
                        && array_diff($metadata['google_fonts']['subsets'], $f['subsets']) === array_diff($f['subsets'], $metadata['google_fonts']['subsets'])
                        && in_array($f['format'], $metadata['google_fonts']['formats'], true)
                );

                $format_precedence = [
                    'woff2' => 1,
                    'woff' => 2,
                    'ttf' => 3,
                    'otf' => 4,
                    'eot' => 5,
                ];

                usort($filtered_m_font_files, static fn ($a, $b) => $format_precedence[$a['format']] <=> $format_precedence[$b['format']]);

                foreach ($filtered_m_font_files as $filtered_m_font_file) {
                    $file_name = sanitize_title_with_dashes(sprintf(
                        'google-fonts-%s-%s-%s-%s-%s-%s',
                        $metadata['google_fonts']['font_data']['slug'], // family
                        $metadata['google_fonts']['font_data']['version'],
                        implode('-', $metadata['google_fonts']['subsets']),
                        $filtered_m_font_file['weight'],
                        $filtered_m_font_file['style'],
                        time()
                    )) . '.' . $filtered_m_font_file['format'];

                    try {
                        $attachment_id = Upload::remote_upload_media($filtered_m_font_file['url'], $file_name, $font_mime_types[$filtered_m_font_file['format']]);

                        if (! $attachment_id) {
                            continue;
                        }
                    } catch (\Throwable $throwable) {
                        //throw $th;
                        continue;
                    }

                    $file = [
                        'uid' => Common::random_slug(10),
                        'attachment_id' => $attachment_id,
                        'attachment_url' => wp_get_attachment_url($attachment_id),
                        'extension' => $filtered_m_font_file['format'],
                        'mime' => $font_mime_types[$filtered_m_font_file['format']],
                        'file_size' => filesize(get_attached_file($attachment_id)),
                        'name' => substr($file_name, 0, strrpos($file_name, '.')),
                    ];

                    $metadata['google_fonts']['font_files'] = array_map(
                        static fn ($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
                            'file' => $file,
                        ]) : $f,
                        $metadata['google_fonts']['font_files']
                    );

                    $metadata['google_fonts']['font_faces'][$k]['attached_font_files'][] = $filtered_m_font_file['uid'];

                    $files[] = $file;
                }

                $font_face['files'] = $files;

                $font_faces[] = $font_face;
            }
        }

        $sql = "
            INSERT INTO {$wpdb->prefix}yabe_webfont_fonts
            (type, title, slug, family, status, metadata, font_faces)
            VALUES
            (%s, %s, %s, %s, %d, %s, %s)
        ";

        $sql = $wpdb->prepare($sql, $type, $title, $slug, $family, $status, json_encode($metadata, JSON_THROW_ON_ERROR), json_encode($font_faces, JSON_THROW_ON_ERROR));

        $wpdb->query($sql);

        $id = $wpdb->insert_id;

        do_action('a!yabe/webfont/api/font:google_fonts_store', $id);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    private function google_fonts_update(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $url_params = $wprestRequest->get_url_params();
        $payload = $wprestRequest->get_json_params();

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

        $title = sanitize_text_field($payload['title']);
        $status = (bool) $payload['status'];

        $metadata = $payload['metadata'];
        $font_faces = [];

        add_filter('wp_check_filetype_and_ext', static fn ($data, $file, $filename, $mimes) => Upload::disable_real_mime_check($data, $file, $filename, $mimes), 10, 4);
        add_filter('upload_mimes', static fn ($mime_types) => Upload::upload_mimes($mime_types, true), 10002);

        $font_mime_types = [
            'woff2' => 'font/woff2',
            'woff' => 'font/woff',
            'ttf' => 'font/ttf',
        ];

        $m_font_faces = $metadata['google_fonts']['font_faces'];
        $m_font_files = $metadata['google_fonts']['font_files'];

        foreach ($m_font_faces as $k => $m_face) {
            $metadata['google_fonts']['font_faces'][$k]['attached_font_files'] = [];

            if (! $m_face['isEnabled']) {
                continue;
            }

            if ($metadata['google_fonts']['variable']) {
                if ($m_face['weight'] !== 0) {
                    continue;
                }

                foreach ($metadata['google_fonts']['subsets'] as $subset) {
                    $filtered_m_font_files = array_filter(
                        $m_font_files,
                        static fn ($f) => $f['weight'] === $m_face['weight']
                            && $f['style'] === $m_face['style']
                            && in_array($subset, $f['subsets'], true)
                            && in_array($f['format'], $metadata['google_fonts']['formats'], true)
                    );

                    foreach ($filtered_m_font_files as $filtered_m_font_file) {
                        $wght = array_filter(
                            $metadata['google_fonts']['font_data']['axes'],
                            static fn ($a) => $a['tag'] === 'wght'
                        );

                        $wdth = array_filter(
                            $metadata['google_fonts']['font_data']['axes'],
                            static fn ($a) => $a['tag'] === 'wdth'
                        );

                        $wdth = array_values($wdth);
                        $wght = array_values($wght);

                        $font_face = [
                            'id' => Common::random_slug(10),
                            'weight' => $wght !== [] ? sprintf('%s %s', $wght[0]['min'], $wght[0]['max']) : '400',
                            'width' => $wdth !== [] ? sprintf('%s%% %s%%', $wdth[0]['min'], $wdth[0]['max']) : '100%',
                            'style' => $m_face['style'],
                            'display' => $m_face['display'],
                            'selector' => $m_face['selector'],
                            'comment' => $m_face['comment'],
                        ];

                        if (array_key_exists('file', $filtered_m_font_file)) {
                            $file = $filtered_m_font_file['file'];
                        } else {
                            $file_name = sanitize_title_with_dashes(sprintf(
                                'google-fonts-%s-%s-%s-%s-%s-%s',
                                $metadata['google_fonts']['font_data']['slug'], // family
                                $metadata['google_fonts']['font_data']['version'],
                                $subset,
                                $filtered_m_font_file['weight'],
                                $filtered_m_font_file['style'],
                                time()
                            )) . '.' . $filtered_m_font_file['format'];

                            try {
                                $attachment_id = Upload::remote_upload_media($filtered_m_font_file['url'], $file_name, $font_mime_types[$filtered_m_font_file['format']]);

                                if (! $attachment_id) {
                                    continue;
                                }
                            } catch (\Throwable $throwable) {
                                //throw $th;
                                continue;
                            }

                            $file = [
                                'uid' => Common::random_slug(10),
                                'attachment_id' => $attachment_id,
                                'attachment_url' => wp_get_attachment_url($attachment_id),
                                'extension' => $filtered_m_font_file['format'],
                                'mime' => $font_mime_types[$filtered_m_font_file['format']],
                                'file_size' => filesize(get_attached_file($attachment_id)),
                                'name' => substr($file_name, 0, strrpos($file_name, '.')),
                            ];

                            $metadata['google_fonts']['font_files'] = array_map(
                                static fn ($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
                                    'file' => $file,
                                ]) : $f,
                                $metadata['google_fonts']['font_files']
                            );
                        }

                        $metadata['google_fonts']['font_faces'][$k]['attached_font_files'][] = $filtered_m_font_file['uid'];

                        $font_face['files'] = [$file];

                        $font_face['unicodeRange'] = $filtered_m_font_file['unicodeRange'];

                        $font_faces[] = $font_face;
                    }
                }
            } else {
                if ($m_face['weight'] === 0) {
                    continue;
                }

                $font_face = [
                    'id' => Common::random_slug(10),
                    'weight' => $m_face['weight'],
                    'width' => $m_face['width'] ?: '100%',
                    'style' => $m_face['style'],
                    'display' => $m_face['display'],
                    'selector' => $m_face['selector'],
                    'comment' => $m_face['comment'],
                    'unicodeRange' => '',
                ];

                $files = [];

                $filtered_m_font_files = array_filter(
                    $m_font_files,
                    static fn ($f) => $f['weight'] === $m_face['weight']
                        && $f['style'] === $m_face['style']
                        && array_diff($metadata['google_fonts']['subsets'], $f['subsets']) === array_diff($f['subsets'], $metadata['google_fonts']['subsets'])
                        && in_array($f['format'], $metadata['google_fonts']['formats'], true)
                );

                $format_precedence = [
                    'woff2' => 1,
                    'woff' => 2,
                    'ttf' => 3,
                    'otf' => 4,
                    'eot' => 5,
                ];

                usort($filtered_m_font_files, static fn ($a, $b) => $format_precedence[$a['format']] <=> $format_precedence[$b['format']]);

                foreach ($filtered_m_font_files as $filtered_m_font_file) {
                    if (array_key_exists('file', $filtered_m_font_file)) {
                        $file = $filtered_m_font_file['file'];
                    } else {
                        $file_name = sanitize_title_with_dashes(sprintf(
                            'google-fonts-%s-%s-%s-%s-%s-%s',
                            $metadata['google_fonts']['font_data']['slug'], // family
                            $metadata['google_fonts']['font_data']['version'],
                            implode('-', $metadata['google_fonts']['subsets']),
                            $filtered_m_font_file['weight'],
                            $filtered_m_font_file['style'],
                            time()
                        )) . '.' . $filtered_m_font_file['format'];

                        try {
                            $attachment_id = Upload::remote_upload_media($filtered_m_font_file['url'], $file_name, $font_mime_types[$filtered_m_font_file['format']]);

                            if (! $attachment_id) {
                                continue;
                            }
                        } catch (\Throwable $throwable) {
                            //throw $th;
                            continue;
                        }

                        $file = [
                            'uid' => Common::random_slug(10),
                            'attachment_id' => $attachment_id,
                            'attachment_url' => wp_get_attachment_url($attachment_id),
                            'extension' => $filtered_m_font_file['format'],
                            'mime' => $font_mime_types[$filtered_m_font_file['format']],
                            'file_size' => filesize(get_attached_file($attachment_id)),
                            'name' => substr($file_name, 0, strrpos($file_name, '.')),
                        ];

                        $metadata['google_fonts']['font_files'] = array_map(
                            static fn ($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
                                'file' => $file,
                            ]) : $f,
                            $metadata['google_fonts']['font_files']
                        );
                    }

                    $metadata['google_fonts']['font_faces'][$k]['attached_font_files'][] = $filtered_m_font_file['uid'];

                    $files[] = $file;
                }

                $font_face['files'] = $files;

                $font_faces[] = $font_face;
            }
        }

        $sql = "
            UPDATE {$wpdb->prefix}yabe_webfont_fonts
            SET title = %s,
                status = %d,
                metadata = %s,
                font_faces = %s
            WHERE id = %d
        ";

        $sql = $wpdb->prepare($sql, $title, $status, json_encode($metadata, JSON_THROW_ON_ERROR), json_encode($font_faces, JSON_THROW_ON_ERROR), $id);

        $wpdb->query($sql);

        do_action('a!yabe/webfont/api/font:google_fonts_update', $id);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }
}
