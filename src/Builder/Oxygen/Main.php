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

namespace Yabe\Webfont\Builder\Oxygen;

use ECF_Plugin;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Plugin;

/**
 * @author Joshua <joshua@rosua.org>
 */
class Main implements BuilderInterface
{
    public function get_name(): string
    {
        return 'oxygen';
    }

    public function __construct()
    {
        add_filter('f!yabe/webfont/builder/oxygen/main:get_font_families', fn ($fonts) => $this->filter_register_fonts($fonts));

        global $ECF_Plugin;
        require_once __DIR__ . '/ECF_Plugin.php';
        $ECF_Plugin = new ECF_Plugin();

        add_action('wp_enqueue_scripts', fn () => $this->enqueue_editor_style(), 1000001);
    }

    public function filter_register_fonts($fonts)
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $families = [];

        $sql = "
            SELECT family FROM {$wpdb->prefix}yabe_webfont_fonts 
            WHERE status = 1
                AND deleted_at IS NULL
        ";

        $result = $wpdb->get_results($sql);

        foreach ($result as $row) {
            $families[] = $row->family;
        }

        return array_merge($fonts, $families);
    }

    public function enqueue_editor_style()
    {
        if (!defined('SHOW_CT_BUILDER')) {
            return;
        }

        wp_enqueue_style('yabe-webfont-for-oxygen-editor', plugin_dir_url(__FILE__) . '/assets/style/editor.css', [], Plugin::VERSION);
    }
}
