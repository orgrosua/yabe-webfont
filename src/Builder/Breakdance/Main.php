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

namespace Yabe\Webfont\Builder\Breakdance;

use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Cache;
use Yabe\Webfont\Core\Runtime;

/**
 * Breakdance integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        remove_action('init', '\Breakdance\GoogleFontsPlugin\loadGoogleFonts');

        add_action('breakdance_loaded', fn () => $this->register_font_families(), 1_000_001);
        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('breakdance'), 1_000_001);
    }

    public function get_name(): string
    {
        return 'breakdance';
    }

    public function register_font_families()
    {
        $font_families = Runtime::get_font_families();

        foreach ($font_families as $font_family) {
            $slug = preg_replace('#[^a-zA-Z0-9\-_]+#', '-', strtolower($font_family['family']));

            $cssName = sprintf('var(--ywf--family-%s)', $slug);
            $dropdownLabel = sprintf('[Yabe] %s', $font_family['title']);
            $fallbackString = '';
            $dependencies = [
                'styles' => [
                    // Cache::get_cache_url(Cache::CSS_CACHE_FILE),
                ],
            ];

            \Breakdance\Fonts\registerFont($slug, $cssName, $dropdownLabel, $fallbackString, $dependencies);
        }
    }
}
