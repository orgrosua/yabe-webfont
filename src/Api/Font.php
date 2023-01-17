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
use Yabe\Webfont\Utils\Upload;

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
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->index($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/custom/store',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->custom_store($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/update-status/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->update_status($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
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
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->destroy($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/restore/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->restore($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/detail/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->detail($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/custom/update/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->custom_update($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/store',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn (\WP_REST_Request $request): \WP_REST_Response => $this->google_fonts_store($request),
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/update/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'google_fonts_update'],
                'permission_callback' => fn (\WP_REST_Request $request): bool => $this->permission_callback($request),
            ]
        );
    }

    public function permission_callback(WP_REST_Request $wprestRequest): bool
    {
        return wp_verify_nonce($wprestRequest->get_header('X-WP-Nonce'), 'wp_rest') && current_user_can('manage_options');
    }

    public function index(WP_REST_Request $wprestRequest): WP_REST_Response
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
                'font_faces' => json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR),
                'font_faces' => $this->attach_font_files(json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR)),
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

    public function detail(WP_REST_Request $wprestRequest): WP_REST_Response
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
            'font_faces' => json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR),
            'font_faces' => $this->attach_font_files(json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR)),
            'status' => (bool) $row->status,
            'created_at' => strtotime($row->created_at),
            'updated_at' => strtotime($row->updated_at),
            'deleted_at' => $row->deleted_at ? strtotime($row->deleted_at) : null,
        ], 200, []);
    }

    public function custom_store(WP_REST_Request $wprestRequest): WP_REST_Response
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

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    public function update_status(WP_REST_Request $wprestRequest): WP_REST_Response
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

        return new WP_REST_Response([
            'id' => $id,
            'status' => $status,
        ], 200, []);
    }

    public function destroy(WP_REST_Request $wprestRequest): WP_REST_Response
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

        return new WP_REST_Response(null, 200, []);
    }

    public function restore(WP_REST_Request $wprestRequest): WP_REST_Response
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

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    public function custom_update(WP_REST_Request $wprestRequest): WP_REST_Response
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

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    public function google_fonts_store(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $payload = $wprestRequest->get_json_params();

        // var_dump($payload);

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

                    foreach ($filtered_m_font_files as $filtered_file) {
                        $wght = array_filter(
                            $metadata['google_fonts']['font_data']['axes'],
                            static fn ($a) => $a['tag'] === 'wght'
                        )[0];

                        $font_face = [
                            'id' => Common::random_slug(10),
                            'weight' => sprintf('%s %s', $wght['min'], $wght['max']),
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
                            $filtered_file['weight'],
                            $filtered_file['style'],
                            time()
                        )) . '.' . $filtered_file['format'];

                        try {
                            $attachment_id = Upload::remote_upload_media($filtered_file['url'], $file_name, $font_mime_types[$filtered_file['format']]);

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
                            'extension' => $filtered_file['format'],
                            'mime' => $font_mime_types[$filtered_file['format']],
                            'file_size' => filesize(get_attached_file($attachment_id)),
                            'name' => substr($file_name, 0, strrpos($file_name, '.')),
                        ];

                        $filtered_file['file'] = $file;

                        $metadata['google_fonts']['font_faces'][$k]['attached_font_files'][] = $filtered_file;

                        $font_face['files'] = [$file];

                        $font_face['unicodeRange'] = $filtered_file['unicodeRange'];

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

                    $filtered_m_font_file['file'] = $file;

                    $metadata['google_fonts']['font_faces'][$k]['attached_font_files'][] = $filtered_m_font_file;

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

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);

        return new WP_REST_Response(null, 200, []);
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
}
