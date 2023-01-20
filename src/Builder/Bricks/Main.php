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

namespace Yabe\Webfont\Builder\Bricks;

use Yabe\Webfont\Builder\BuilderInterface;

/**
 * @author Joshua <joshua@rosua.org>
 */
class Main implements BuilderInterface
{
    public function get_name(): string
    {
        return 'bricks';
    }

    public function __construct()
    {
        add_filter('bricks/builder/standard_fonts', fn ($fonts) => $this->filter_register_fonts($fonts));
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
}
