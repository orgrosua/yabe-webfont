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

namespace Yabe\Webfont\Builder\Etch;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Cache;
use Yabe\Webfont\Utils\Font;
use YABE_WEBFONT;

/**
 * Etch integration.
 *
 * @author Joshua Gugun Siagian <suabahasa@gmail.com>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('etch/canvas/additional_stylesheets', fn ($stylesheets) => $this->filter_additional_stylesheets($stylesheets), 1_000_001);
        add_action('wp_enqueue_scripts', fn () => $this->enqueue_scripts(), 1_000_001);
    }

    public function get_name(): string
    {
        return 'etch';
    }

    public function enqueue_scripts(): void
    {
        if (! (isset($_GET['etch']) && $_GET['etch'] === 'magic')) {
            return;
        }

        wp_enqueue_script('yabe-webfont-for-etch', plugin_dir_url(__FILE__) . 'assets/etch.js', [], YABE_WEBFONT::VERSION, true);
        wp_localize_script('yabe-webfont-for-etch', 'yabeWebfontEtch', [
            'font_families' => array_map(static fn ($font) => [
                'name' => $font['title'],
                'key' => $font['css']['variable'],
                'family' => $font['family'],
            ], Font::get_fonts()),
        ]);
    }

    public function filter_additional_stylesheets($stylesheets): array
    {
        if (! is_array($stylesheets)) {
            return $stylesheets;
        }

        $stylesheet_url = Cache::get_cache_url(Cache::CSS_CACHE_FILE);

        foreach ($stylesheets as $stylesheet) {
            if (! is_array($stylesheet)) {
                continue;
            }

            if (($stylesheet['id'] ?? '') === 'yabe-webfont-cache-css' || ($stylesheet['url'] ?? '') === $stylesheet_url) {
                return $stylesheets;
            }
        }

        $stylesheets[] = [
            'id' => 'yabe-webfont-cache-css',
            'url' => $stylesheet_url,
        ];

        return $stylesheets;
    }
}
