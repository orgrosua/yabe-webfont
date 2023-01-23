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

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * Cwicly integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_action('a!yabe/webfont/core/cache:build_cache', fn () => $this->cwicly_build_cache());
    }

    public function get_name(): string
    {
        return 'cwicly';
    }

    public function cwicly_build_cache()
    {
        if (! defined('CWICLY_VERSION')) {
            return;
        }

        $font_cols = get_option('cwicly_font_cols', []);

        if (! is_array($font_cols)) {
            $font_cols = json_decode($font_cols, true, 512, JSON_THROW_ON_ERROR);
        }

        $font_families = Runtime::get_font_families();

        foreach ($font_cols as $k => $v) {
            if ($v === []) {
                unset($font_cols[$k]);
            }
        }

        foreach ($font_families as $font_family) {
            if (! array_key_exists($font_family['family'], $font_cols)) {
                $font_cols[$font_family['family']] = [];
            }
        }

        $font_cols = $font_cols !== [] ? json_encode($font_cols, JSON_THROW_ON_ERROR) : '{}';

        update_option('cwicly_font_cols', $font_cols);
    }
}
