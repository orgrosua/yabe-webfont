<?php

/*
 * This file is part of the Yabe package.
 *
 * (c) Joshua Gugun Siagian <suabahasa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Yabe\Webfont\Api;

use Sabberworm\CSS\Parser;
use stdClass;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use wpdb;
use Yabe\Webfont\Core\Cache;
use Yabe\Webfont\Utils\Common;
use Yabe\Webfont\Utils\Config;
use Yabe\Webfont\Utils\Upload;
use YABE_WEBFONT;

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
            'a!yabe/webfont/api/font:import',
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
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->index($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/custom/store',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->custom_store($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/update-status/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->update_status($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
                'args' => [
                    'status' => [
                        'required' => true,
                        'validate_callback' => static fn($param): bool => is_bool($param),
                    ],
                ],
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/delete/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::DELETABLE, // not working on IIS server without further configuration
                'methods' => 'POST, DELETE',
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->destroy($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/restore/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->restore($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/export',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->export($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/import',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->import($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/detail/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->detail($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/custom/update/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->custom_update($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/store',
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->google_fonts_store($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/update/(?P<id>\d+)',
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->google_fonts_update($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/metadata',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->google_fonts_metadata($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
            ]
        );

        register_rest_route(
            self::API_NAMESPACE,
            $this->get_prefix() . '/google-fonts/webfonts/(?P<slug>[a-zA-Z0-9_\-+\.]+)',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => fn(\WP_REST_Request $wprestRequest): \WP_REST_Response => $this->google_fonts_webfonts($wprestRequest),
                'permission_callback' => fn(\WP_REST_Request $wprestRequest): bool => $this->permission_callback($wprestRequest),
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
            try {
                $metadata = json_decode($row->metadata, null, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $metadata = json_decode(gzuncompress(base64_decode($row->metadata)), null, 512, JSON_THROW_ON_ERROR);
            }

            try {
                $font_faces = json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $font_faces = json_decode(gzuncompress(base64_decode($row->font_faces)), null, 512, JSON_THROW_ON_ERROR);
            }

            $items[] = [
                'id' => $row->id,
                'type' => $row->type,
                'title' => $row->title,
                'slug' => $row->slug,
                'family' => $row->family,
                'metadata' => $metadata,
                'font_faces' => Upload::refresh_font_faces_attachment_url($font_faces),
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

        try {
            $metadata = json_decode($row->metadata, null, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $metadata = json_decode(gzuncompress(base64_decode($row->metadata)), null, 512, JSON_THROW_ON_ERROR);
        }

        try {
            $font_faces = json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $font_faces = json_decode(gzuncompress(base64_decode($row->font_faces)), null, 512, JSON_THROW_ON_ERROR);
        }

        $payload = [
            'id' => $row->id,
            'type' => $row->type,
            'title' => $row->title,
            'slug' => $row->slug,
            'family' => $row->family,
            'metadata' => $metadata,
            'font_faces' => Upload::refresh_font_faces_attachment_url($font_faces),
            'status' => (bool) $row->status,
            'created_at' => strtotime($row->created_at),
            'updated_at' => strtotime($row->updated_at),
            'deleted_at' => $row->deleted_at ? strtotime($row->deleted_at) : null,
        ];

        if (property_exists($payload['metadata'], 'google_fonts')) {
            $payload['metadata']->google_fonts->font_files = Upload::refresh_google_fonts_attachment_url($payload['metadata']->google_fonts->font_files);
        }

        return new WP_REST_Response($payload, 200, []);
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

        $wpdb->insert(sprintf('%syabe_webfont_fonts', $wpdb->prefix), [
            'type' => $type,
            'title' => $title,
            'slug' => $slug,
            'family' => $family,
            'status' => $status,
            'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR),
            'font_faces' => json_encode($font_faces, JSON_THROW_ON_ERROR),
        ], [
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
        ]);

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

        $wpdb->update(sprintf('%syabe_webfont_fonts', $wpdb->prefix), [
            'status' => $status,
        ], [
            'id' => $id,
        ], [
            '%d',
        ], [
            '%d',
        ]);

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
            SELECT * FROM {$wpdb->prefix}yabe_webfont_fonts
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

        // delete attachment from wordpress media library
        if ($item->deleted_at) {
            try {
                $font_faces = json_decode($item->font_faces, null, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $font_faces = json_decode(gzuncompress(base64_decode($item->font_faces)), null, 512, JSON_THROW_ON_ERROR);
            }

            foreach ($font_faces as $font_face) {
                if ($font_face->files !== []) {
                    foreach ($font_face->files as $f) {
                        wp_delete_attachment($f->attachment_id, true);
                    }
                }
            }
        }

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

        $wpdb->update(sprintf('%syabe_webfont_fonts', $wpdb->prefix), [
            'deleted_at' => null,
        ], [
            'id' => $id,
        ], [
            '%s',
        ], [
            '%d',
        ]);

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

        $wpdb->update(
            sprintf('%syabe_webfont_fonts', $wpdb->prefix),
            [
                'title' => $title,
                'family' => $family,
                'status' => $status,
                'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR),
                'font_faces' => json_encode($font_faces, JSON_THROW_ON_ERROR),
            ],
            [
                'id' => $id,
            ],
            [
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
            ],
            [
                '%d',
            ],
        );

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

        add_filter('wp_check_filetype_and_ext', static fn($data, $file, $filename, $mimes) => Upload::disable_real_mime_check($data, $file, $filename, $mimes), 10, 4);
        add_filter('upload_mimes', static fn($mime_types) => Upload::upload_mimes($mime_types, true), 1_000_001);

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

                $all_filtered_m_font_files = [];

                foreach ($metadata['google_fonts']['subsets'] as $subset) {
                    $filtered_m_font_files = array_filter(
                        $m_font_files,
                        static fn($f) => $f['weight'] === $m_face['weight']
                            && $f['style'] === $m_face['style']
                            && in_array($subset, $f['subsets'], true)
                            && in_array($f['format'], $metadata['google_fonts']['formats'], true)
                    );

                    $all_filtered_m_font_files = array_merge($all_filtered_m_font_files, $filtered_m_font_files);
                }

                // variableFontFiles
                $var_filtered_m_font_files = array_filter(
                    $m_font_files,
                    static fn($f) => $f['weight'] === $m_face['weight']
                        && $f['style'] === $m_face['style']
                        && in_array($f['format'], $metadata['google_fonts']['formats'], true)

                        // if any of the element in $metadata['google_fonts']['subsets'] has digit/number
                        && array_reduce(
                            $metadata['google_fonts']['subsets'],
                            static fn($carry, $subset) => $carry && ! preg_match('/\d/', $subset),
                            true
                        )
                );

                // merge and remove duplicates
                $all_filtered_m_font_files = array_values(array_unique(array_merge($all_filtered_m_font_files, $var_filtered_m_font_files), SORT_REGULAR));

                foreach ($all_filtered_m_font_files as $filtered_m_font_file) {
                    $wght = array_filter(
                        $metadata['google_fonts']['font_data']['axes'],
                        static fn($a) => $a['tag'] === 'wght'
                    );

                    $wdth = array_filter(
                        $metadata['google_fonts']['font_data']['axes'],
                        static fn($a) => $a['tag'] === 'wdth'
                    );

                    $slnt = array_filter(
                        $metadata['google_fonts']['font_data']['axes'],
                        static fn($a) => $a['tag'] === 'slnt'
                    );

                    $wdth = array_values($wdth);
                    $wght = array_values($wght);
                    $slnt = array_values($slnt);

                    $font_face = [
                        'id' => Common::random_slug(10),
                        'weight' => $wght !== [] ? sprintf('%s %s', $wght[0]['min'], $wght[0]['max']) : '400',
                        'width' => $wdth !== [] ? sprintf('%s%% %s%%', $wdth[0]['min'], $wdth[0]['max']) : '100%',
                        'style' => $slnt !== [] ? sprintf('oblique %sdeg %sdeg', $slnt[0]['max'] * -1, $slnt[0]['min'] * -1) : $m_face['style'],
                        'display' => $m_face['display'],
                        'selector' => $m_face['selector'],
                        'comment' => $m_face['comment'],
                        'preload' => $m_face['preload'],
                    ];

                    $file_name = sanitize_title_with_dashes(sprintf(
                        'google-fonts-%s-%s-%s-var-%s-%s',
                        $metadata['google_fonts']['font_data']['slug'], // family
                        $metadata['google_fonts']['font_data']['version'],
                        implode('_', $filtered_m_font_file['subsets']),
                        Common::random_slug(5),
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
                        static fn($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
                            'file' => $file,
                        ]) : $f,
                        $metadata['google_fonts']['font_files']
                    );

                    $metadata['google_fonts']['font_faces'][$k]['attached_font_files'][] = $filtered_m_font_file['uid'];

                    $font_face['files'] = [$file];

                    $font_face['unicodeRange'] = $filtered_m_font_file['unicodeRange'];

                    $font_faces[] = $font_face;
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
                    'preload' => $m_face['preload'],
                ];

                $files = [];

                $filtered_m_font_files = array_filter(
                    $m_font_files,
                    static fn($f) => $f['weight'] === $m_face['weight']
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

                usort($filtered_m_font_files, static fn($a, $b) => $format_precedence[$a['format']] <=> $format_precedence[$b['format']]);

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
                        static fn($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
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

        $compressed_metadata = base64_encode(gzcompress(json_encode($metadata, JSON_THROW_ON_ERROR), 9));
        $compressed_font_faces = base64_encode(gzcompress(json_encode($font_faces, JSON_THROW_ON_ERROR), 9));

        $wpdb->insert(
            sprintf('%syabe_webfont_fonts', $wpdb->prefix),
            [
                'type' => $type,
                'title' => $title,
                'slug' => $slug,
                'family' => $family,
                'status' => $status,
                'metadata' => $compressed_metadata,
                'font_faces' => $compressed_font_faces, //json_encode($font_faces, JSON_THROW_ON_ERROR),
            ],
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
            ]
        );

        // get wpdb error
        // error_log(print_r($wpdb->last_error, true));

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

        add_filter('wp_check_filetype_and_ext', static fn($data, $file, $filename, $mimes) => Upload::disable_real_mime_check($data, $file, $filename, $mimes), 10, 4);
        add_filter('upload_mimes', static fn($mime_types) => Upload::upload_mimes($mime_types, true), 1_000_001);

        $this->google_fonts_update_filter($metadata, $font_faces);

        $compressed_metadata = base64_encode(gzcompress(json_encode($metadata, JSON_THROW_ON_ERROR), 9));
        $compressed_font_faces = base64_encode(gzcompress(json_encode($font_faces, JSON_THROW_ON_ERROR), 9));

        $wpdb->update(
            sprintf('%syabe_webfont_fonts', $wpdb->prefix),
            [
                'title' => $title,
                'status' => $status,
                'metadata' => $compressed_metadata,
                'font_faces' => $compressed_font_faces, //json_encode($font_faces, JSON_THROW_ON_ERROR),
            ],
            [
                'id' => $id,
            ],
            [
                '%s',
                '%d',
                '%s',
                '%s',
            ],
            [
                '%d',
            ]
        );

        do_action('a!yabe/webfont/api/font:google_fonts_update', $id);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    private function export(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $params = $wprestRequest->get_json_params();

        $items = $params['items'];

        if (! is_array($items) || $items === []) {
            return new WP_REST_Response([
                'message' => 'No items to export',
            ], 400, []);
        }

        $is_bundled = Config::get('misc.export_bundle_binary', false);

        $placeholder = implode(',', array_fill(0, count($items), '%d'));

        $sql = "
            SELECT * FROM {$wpdb->prefix}yabe_webfont_fonts
            WHERE id IN ({$placeholder})
        ";

        $sql = $wpdb->prepare($sql, $items);

        $rows = $wpdb->get_results($sql);

        $items = [];

        foreach ($rows as $row) {
            try {
                $font_faces = json_decode($row->font_faces, null, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $font_faces = json_decode(gzuncompress(base64_decode($row->font_faces)), null, 512, JSON_THROW_ON_ERROR);
            }

            try {
                $metadata = json_decode($row->metadata, null, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $metadata = json_decode(gzuncompress(base64_decode($row->metadata)), null, 512, JSON_THROW_ON_ERROR);
            }

            if ($row->type === 'adobe-fonts') {
                continue;
            } elseif ($row->type === 'custom') {
                foreach ($font_faces as $i => $font_face) {
                    foreach ($font_face->files as $j => $file) {
                        // bundle binary and encode it to base64 if enabled
                        if ($is_bundled) {
                            $file_path = get_attached_file($file->attachment_id);
                            if ($file_path) {
                                $font_faces[$i]->files[$j]->binary = base64_encode(file_get_contents($file_path));
                            }
                            unset($font_faces[$i]->files[$j]->attachment_url);
                        } else {
                            $attachment_url = wp_get_attachment_url($file->attachment_id);
                            if ($attachment_url) {
                                $parsed = parse_url($attachment_url);
                                $font_faces[$i]->files[$j]->attachment_url = $parsed['path'];
                            }
                        }

                        unset($font_faces[$i]->files[$j]->attachment_id);
                    }
                }
            } elseif ($row->type === 'google-fonts') {
                // minimize metadata
                $font_faces = [];

                if (property_exists($metadata, 'google_fonts')) {
                    foreach ($metadata->google_fonts->font_files as $i => $font_file) {
                        if (property_exists($font_file, 'file')) {
                            unset($metadata->google_fonts->font_files[$i]->file);
                        }
                    }

                    foreach ($metadata->google_fonts->font_faces as $i => $font_face) {
                        if (property_exists($font_face, 'attached_font_files')) {
                            unset($metadata->google_fonts->font_faces[$i]->attached_font_files);
                        }
                    }
                }
            }

            $item = [
                'type' => $row->type,
                'title' => $row->title,
                'slug' => $row->slug,
                'family' => $row->family,
            ];

            $item['font_faces'] = base64_encode(json_encode($font_faces, JSON_THROW_ON_ERROR));
            $item['metadata'] = base64_encode(json_encode($metadata, JSON_THROW_ON_ERROR));

            $items[] = $item;
        }

        $data = [
            'module_id' => YABE_WEBFONT::WP_OPTION,
            'version' => YABE_WEBFONT::VERSION,
            'export_time' => time(),
            'site_url' => site_url(),
            'is_bundled' => $is_bundled,
            'items' => $items,
        ];

        return new WP_REST_Response([
            'data' => $data,
        ], 200);
    }

    private function import(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $params = $wprestRequest->get_json_params();

        $site_url = $params['site_url'];
        $version = $params['version'];
        $is_bundled = $params['is_bundled'];
        $item = $params['item'];

        $type = $item['type'];
        $title = sanitize_text_field($item['title']);
        $slug = Common::random_slug(10);
        $family = sanitize_text_field($item['family']);
        $status = true;

        $font_faces = json_decode(base64_decode($item['font_faces'], true), null, 512, JSON_THROW_ON_ERROR);
        $metadata = json_decode(base64_decode($item['metadata'], true), null, 512, JSON_THROW_ON_ERROR);

        add_filter('wp_check_filetype_and_ext', static fn($data, $file, $filename, $mimes) => Upload::disable_real_mime_check($data, $file, $filename, $mimes), 10, 4);
        add_filter('upload_mimes', static fn($mime_types) => Upload::upload_mimes($mime_types, true), 1_000_001);

        if ($item['type'] === 'adobe-fonts') {
            return new WP_REST_Response([
                'message' => 'Adobe Fonts is not importable',
            ], 400, []);
        } elseif ($item['type'] === 'google-fonts') {
            $font_faces = json_decode(base64_decode($item['font_faces'], true), true, 512, JSON_THROW_ON_ERROR);
            $metadata = json_decode(base64_decode($item['metadata'], true), true, 512, JSON_THROW_ON_ERROR);
            $this->google_fonts_update_filter($metadata, $font_faces);
        } elseif ($item['type'] === 'custom') {
            foreach ($font_faces as $i => $font_face) {
                foreach ($font_face->files as $j => $file) {
                    // if not first-hand
                    $font_faces[$i]->files[$j]->name = preg_replace('#\-[\_\-a-zA-Z0-9]{5}\-\d{10}$#', '', $file->name);

                    // if explicit using a Google Fonts file
                    $font_faces[$i]->files[$j]->name = preg_replace('#\-\d{10}\-(woff2|woff|ttf)$#', '', $file->name);

                    $file_name = sanitize_title_with_dashes(sprintf(
                        '%s-%s-%s',
                        $font_faces[$i]->files[$j]->name,
                        Common::random_slug(5),
                        time()
                    )) . '.' . $file->extension;

                    try {
                        if ($is_bundled) {
                            $attachment_id = Upload::binary_upload_media(base64_decode($file->binary, true), $file_name, $file->mime);
                            unset($font_faces[$i]->files[$j]->binary);
                        } else {
                            $attachment_id = Upload::remote_upload_media($site_url . $file->attachment_url, $file_name, $file->mime);
                        }
                    } catch (\Throwable $throwable) {
                        //throw $th;
                        continue;
                    }

                    if (! $attachment_id || is_wp_error($attachment_id)) {
                        continue;
                    }

                    $font_faces[$i]->files[$j]->attachment_id = $attachment_id;
                    $font_faces[$i]->files[$j]->attachment_url = wp_get_attachment_url($attachment_id);
                }
            }
        } else {
            return new WP_REST_Response([
                'message' => 'Invalid item type',
            ], 400, []);
        }

        $metadata = base64_encode(gzcompress(json_encode($metadata, JSON_THROW_ON_ERROR), 9));
        $font_faces = base64_encode(gzcompress(json_encode($font_faces, JSON_THROW_ON_ERROR), 9));

        $wpdb->insert(sprintf('%syabe_webfont_fonts', $wpdb->prefix), [
            'type' => $type,
            'title' => $title,
            'slug' => $slug,
            'family' => $family,
            'status' => $status,
            'metadata' => $metadata, //json_encode($metadata, JSON_THROW_ON_ERROR),
            'font_faces' => $font_faces, //json_encode($font_faces, JSON_THROW_ON_ERROR),
        ], [
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
        ]);

        $id = $wpdb->insert_id;

        do_action('a!yabe/webfont/api/font:import', $id);

        return new WP_REST_Response([
            'id' => $id,
        ], 200, []);
    }

    private function google_fonts_update_filter(&$metadata, &$font_faces)
    {
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

                // TODO: variable font with subset number/digit

                $all_filtered_m_font_files = [];

                foreach ($metadata['google_fonts']['subsets'] as $subset) {
                    $filtered_m_font_files = array_filter(
                        $m_font_files,
                        static fn($f) => $f['weight'] === $m_face['weight']
                            && $f['style'] === $m_face['style']
                            && in_array($subset, $f['subsets'], true)
                            && in_array($f['format'], $metadata['google_fonts']['formats'], true)
                    );

                    $all_filtered_m_font_files = array_merge($all_filtered_m_font_files, $filtered_m_font_files);
                }

                // variableFontFiles
                $var_filtered_m_font_files = array_filter(
                    $m_font_files,
                    static fn($f) => $f['weight'] === $m_face['weight']
                        && $f['style'] === $m_face['style']
                        && in_array($f['format'], $metadata['google_fonts']['formats'], true)

                        // if any of the element in $metadata['google_fonts']['subsets'] has digit/number
                        && array_reduce(
                            $metadata['google_fonts']['subsets'],
                            static fn($carry, $subset) => $carry && ! preg_match('/\d/', $subset),
                            true
                        )
                );

                foreach ($filtered_m_font_files as $filtered_m_font_file) {
                    $wght = array_filter(
                        $metadata['google_fonts']['font_data']['axes'],
                        static fn($a) => $a['tag'] === 'wght'
                    );

                    $wdth = array_filter(
                        $metadata['google_fonts']['font_data']['axes'],
                        static fn($a) => $a['tag'] === 'wdth'
                    );

                    $slnt = array_filter(
                        $metadata['google_fonts']['font_data']['axes'],
                        static fn($a) => $a['tag'] === 'slnt'
                    );

                    $wdth = array_values($wdth);
                    $wght = array_values($wght);
                    $slnt = array_values($slnt);

                    $font_face = [
                        'id' => Common::random_slug(10),
                        'weight' => $wght !== [] ? sprintf('%s %s', $wght[0]['min'], $wght[0]['max']) : '400',
                        'width' => $wdth !== [] ? sprintf('%s%% %s%%', $wdth[0]['min'], $wdth[0]['max']) : '100%',
                        'style' => $slnt !== [] ? sprintf('oblique %sdeg %sdeg', $slnt[0]['max'] * -1, $slnt[0]['min'] * -1) : $m_face['style'],
                        'display' => $m_face['display'],
                        'selector' => $m_face['selector'],
                        'comment' => $m_face['comment'],
                        'preload' => $m_face['preload'],
                    ];

                    if (array_key_exists('file', $filtered_m_font_file)) {
                        $file = $filtered_m_font_file['file'];
                    } else {
                        $file_name = sanitize_title_with_dashes(sprintf(
                            'google-fonts-%s-%s-%s-var-%s-%s',
                            $metadata['google_fonts']['font_data']['slug'], // family
                            $metadata['google_fonts']['font_data']['version'],
                            implode('_', $filtered_m_font_file['subsets']),
                            Common::random_slug(5),
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
                            static fn($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
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
                    'preload' => $m_face['preload'],
                ];

                $files = [];

                $filtered_m_font_files = array_filter(
                    $m_font_files,
                    static fn($f) => $f['weight'] === $m_face['weight']
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

                usort($filtered_m_font_files, static fn($a, $b) => $format_precedence[$a['format']] <=> $format_precedence[$b['format']]);

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
                            static fn($f) => $f['uid'] === $filtered_m_font_file['uid'] ? array_merge($f, [
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
    }

    private function update_google_fonts_metadata()
    {
        $file_path = Cache::get_cache_path('webfonts.json');
        $cdn_url = apply_filters('f!yabe/webfont/font:google_fonts.metadata.file_url', 'https://cdn.jsdelivr.net/gh/orgrosua/yabe-webfont@latest/fonts.json');

        $temp_file = download_url($cdn_url);

        if (is_wp_error($temp_file)) {
            throw new \Exception(__('Failed to download metadata file', 'yabe-webfont'));
        }

        Common::save_file(file_get_contents($temp_file), $file_path);
    }

    private function google_fonts_metadata(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        $file_path = Cache::get_cache_path('webfonts.json');

        if (apply_filters('f!yabe/webfont/font:google_fonts.metadata.force_cdn', false)) {

            try {
                $this->update_google_fonts_metadata();
            } catch (\Exception $e) {
                return new WP_REST_Response([
                    'message' => $e->getMessage(),
                ], 500, []);
            }

            $metadata = json_decode(file_get_contents($file_path), null, 512, JSON_THROW_ON_ERROR);

            return new WP_REST_Response([
                'fonts' => $metadata,
            ], 200, []);
        }

        if (!file_exists($file_path)) {
            $payload = file_get_contents(dirname(YABE_WEBFONT::FILE) . '/fonts.json');
            Common::save_file($payload, $file_path);
        }

        if (apply_filters('f!yabe/webfont/font:google_fonts.metadata.enable_update', true) !== false) {
            if (filemtime($file_path) < strtotime('-7 days')) {
                try {
                    $this->update_google_fonts_metadata();
                } catch (\Exception $e) {
                    return new WP_REST_Response([
                        'message' => $e->getMessage(),
                    ], 500, []);
                }
            }
        }

        $metadata = json_decode(file_get_contents($file_path), null, 512, JSON_THROW_ON_ERROR);

        return new WP_REST_Response([
            'fonts' => $metadata,
        ], 200, []);
    }

    private function google_fonts_webfonts(WP_REST_Request $wprestRequest): WP_REST_Response
    {
        $url_params = $wprestRequest->get_url_params();
        $query = $wprestRequest->get_query_params();

        $slug = $url_params['slug'] ?? '';

        if ('' === trim($slug)) {
            return new WP_REST_Response([
                'message' => __('Slug is required', 'yabe-webfont'),
            ], 400, []);
        }

        // retrieve metadata
        $file_path = Cache::get_cache_path('webfonts.json');
        if (!file_exists($file_path)) {
            $payload = file_get_contents(dirname(YABE_WEBFONT::FILE) . '/fonts.json');
            Common::save_file($payload, $file_path);
        }

        $metadata = json_decode(file_get_contents($file_path), null, 512, JSON_THROW_ON_ERROR);

        // search for the font by slug
        $font = array_filter($metadata, static fn($font) => $font->slug === $slug);

        if (empty($font)) {
            return new WP_REST_Response([
                'message' => __('Font not found', 'yabe-webfont'),
            ], 404, []);
        }

        $font = array_values($font)[0]; // get the first element of the array

        $subsets = $query['subsets'];

        if (! $subsets) {
            $subsets = in_array('latin', $font->subsets, true)
                ? ['latin']
                : [$font->subsets[0]];
        } else {
            $querySubsets = explode(',', $subsets);
            $subsets = array_intersect($font->subsets, $querySubsets);
        }

        $subsets = array_unique($subsets);
        sort($subsets);

        $cache_key = 'yabe_webfont_google_fonts_files_' . md5($slug . ':' . implode(',', $subsets));

        $cachedFontFiles = get_transient($cache_key);

        if ($cachedFontFiles) {
            return new WP_REST_Response([
                'font' => $font,
                'files' => array_values($cachedFontFiles),
            ], 200, []);
        }

        $variantKeys = $font->variants;

        $fontFormats = ['woff2', 'woff', 'ttf'];

        foreach ($variantKeys as $variantKey) {
            foreach ($fontFormats as $fontFormat) {
                $fontUrl = $this->fetch_google_font_file($font, $fontFormat, $variantKey, $subsets);

                $newFontFile = new stdClass();

                $newFontFile->format = $fontFormat;
                $newFontFile->weight = intval($variantKey);

                switch (preg_replace('/\d/', '', (string) $variantKey)) {
                    case 'i':
                        $newFontFile->style = 'italic';
                        break;
                    case 'o':
                        $newFontFile->style = 'oblique';
                        break;
                    default:
                        $newFontFile->style = 'normal';
                }

                $newFontFile->subsets = $subsets;
                $newFontFile->url = $fontUrl;
                $font->files[] = $newFontFile;
            }
        }

        if (!empty($font->axes)) {
            $italics = [];

            if (count(array_filter($variantKeys, static fn(string $variantKey) => preg_replace('/\d/', '', $variantKey) === '')) > 0) {
                array_push($italics, 0);
            }

            if (count(array_filter($variantKeys, static fn(string $variantKey) => preg_replace('/\d/', '', $variantKey) === 'i')) > 0) {
                array_push($italics, 1);
            }

            foreach ($italics as $italic) {
                switch ($italic) {
                    case 0:
                        $style = 'normal';
                        break;
                    case 1:
                        $style = 'italic';
                        break;
                    default:
                        $style = 'normal';
                }

                $filteredFontFilesVariable = array_filter($font->files, static fn($fontFile) => $fontFile->format === 'woff2'
                    && $fontFile->weight === 0
                    && $fontFile->style === $style
                    && in_array($fontFile->subsets[0], $subsets, true));

                if (count($filteredFontFilesVariable) < count($subsets)) {

                    $fetchedVariableFonts = $this->fetch_google_font_variable_file($font, $italic, $font->axes);

                    foreach ($fetchedVariableFonts as $fetchedVariableFont) {
                        $existFilteredFontFilesVariable = array_filter($filteredFontFilesVariable, static fn($fontFile) => $fontFile->format === 'woff2'
                            && $fontFile->weight === 0
                            && $fontFile->style === $style
                            && in_array($fetchedVariableFont['subset'], $fontFile->subsets, true));

                        if (empty($existFilteredFontFilesVariable)) {
                            $newFontFile = new stdClass();

                            $newFontFile->format = 'woff2';
                            $newFontFile->weight = 0;
                            $newFontFile->style = $style;
                            $newFontFile->subsets = [$fetchedVariableFont['subset']];
                            $newFontFile->url = $fetchedVariableFont['url'];
                            $newFontFile->unicodeRange = $fetchedVariableFont['unicodeRange'];

                            $font->files[] = $newFontFile;
                        }
                    }
                }
            }
        }

        $filteredFontFiles = array_filter($font->files, static function ($fontFile) use ($subsets) {
            $fontFileSubsets = array_unique($fontFile->subsets);
            sort($fontFileSubsets);

            $variableNumberedSubsets = false;

            if ($fontFile->weight === 0) {
                foreach ($fontFile->subsets as $ffSubset) {
                    if (preg_match('/\d/', $ffSubset)) {
                        $variableNumberedSubsets = true;
                        break;
                    }
                }
            }

            return $fontFileSubsets === $subsets || $variableNumberedSubsets;
        });

        // Cache the result for 1 day
        set_transient($cache_key, $filteredFontFiles, DAY_IN_SECONDS);

        unset($font->files);

        return new WP_REST_Response([
            'font' => $font,
            'files' => array_values($filteredFontFiles),
        ], 200, []);
    }

    private function fetch_google_font_file($font, string $format, string $variant, array $subsets): string
    {
        switch ($format) {
            case 'woff2':
                $userAgent = YABE_WEBFONT::USER_AGENTS['WOFF2'];
                break;
            case 'woff':
                $userAgent = YABE_WEBFONT::USER_AGENTS['WOFF'];
                break;
            case 'ttf':
                $userAgent = YABE_WEBFONT::USER_AGENTS['TTF'];
                break;
            default:
                $userAgent = apply_filters('f!yabe/webfont/font:google_fonts.fetch.user_agent.default', YABE_WEBFONT::USER_AGENTS['CURRENT']);
        }

        $family = str_replace(' ', '+', $font->family);

        $url = sprintf(
            'https://fonts.googleapis.com/css?family=%s:%s&subset=%s',
            $family,
            $variant,
            implode(',', $subsets)
        );

        $response = wp_remote_get($url, [
            'headers' => [
                'User-Agent' => $userAgent,
            ],
        ]);

        if (is_wp_error($response)) {
            throw new \Exception(__('Failed to fetch font file', 'yabe-webfont'));
        }

        $cssDocument = (new Parser(wp_remote_retrieve_body($response)))->parse();

        $fontUrl = $cssDocument->getContents()[0]->getRules('src')[0]->getValue()->getListComponents()[0]->getURL()->getString();

        return $fontUrl;
    }

    private function negative_case(string $string): string
    {
        $arr = str_split($string);

        foreach ($arr as $key => $char) {
            $arr[$key] = ctype_upper($char) ? strtolower($char) : strtoupper($char);
        }

        return implode('', $arr);
    }

    /**
     * @see https://developers.google.com/fonts/docs/css2#api_url_specification
     */
    private function fetch_google_font_variable_file($font, int $italic, array $axes): array
    {
        $axis_tag_list = [];
        $axis_tuple_list = [];
        $family = str_replace(' ', '+', $font->family);

        // convert axes to array
        $axes = array_map(static fn($axis) => [
            'tag' => $axis->tag,
            'min' => $axis->min ?? null,
            'max' => $axis->max ?? null,
            'defaultValue' => $axis->defaultValue ?? null,
        ], $axes);

        // add ital axis
        $axes[] = [
            'tag' => 'ital',
            'defaultValue' => $italic,
        ];

        // sort axes by "tag" alphabetically (e.g. a,b,c,A,B,C)
        usort($axes, fn(array $a, array $b) => strcmp((string) $this->negative_case($a['tag']), (string) $this->negative_case($b['tag'])));

        foreach ($axes as $a) {
            $axis_tag_list[] = $a['tag'];

            if (array_key_exists('min', $a) && array_key_exists('max', $a)) {
                $axis_tuple_list[] = sprintf('%s..%s', $a['min'], $a['max']);
            } else {
                $axis_tuple_list[] = sprintf('%s', $a['defaultValue']);
            }
        }

        $url = sprintf(
            'https://fonts.googleapis.com/css2?family=%s:%s@%s',
            $family,
            implode(',', $axis_tag_list),
            implode(',', $axis_tuple_list)
        );

        $parsedFiles = [];

        $response = wp_remote_get($url, [
            'headers' => [
                'User-Agent' => apply_filters('f!yabe/webfont/font:google_fonts.fetch.user_agent.default', YABE_WEBFONT::USER_AGENTS['CURRENT']),
            ],
        ]);

        if (is_wp_error($response)) {
            throw new \Exception(__('Failed to fetch variable font file', 'yabe-webfont'));
        }

        // parse the css
        $cssDocument = (new Parser(wp_remote_retrieve_body($response)))->parse();

        // match all comment /* comment */
        preg_match_all('/\/\*(.*?)\*\//s', wp_remote_retrieve_body($response), $parsedComments);

        $comments = array_map(static fn(string $comment) => trim($comment), $parsedComments[1]);

        $cssContents = $cssDocument->getContents();

        for ($i = 0; $i < count($cssContents); $i++) {
            $subset = $comments[$i];
            $url = $cssContents[$i]->getRules('src')[0]->getValue()->getListComponents()[0]->getURL()->getString();

            $unicodeRangeRuleSet = $cssContents[$i]->getRules('unicode-range')[0]->getValue();

            $unicodeRange = $unicodeRangeRuleSet instanceof \Sabberworm\CSS\Value\RuleValueList
                ? implode(', ', $unicodeRangeRuleSet->getListComponents())
                : $unicodeRangeRuleSet;

            $parsedFiles[] = [
                'subset' => $subset,
                'url' => $url,
                'unicodeRange' => $unicodeRange,
            ];
        }

        return $parsedFiles;
    }
}
