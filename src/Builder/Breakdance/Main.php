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

namespace Yabe\Webfont\Builder\Breakdance;

use Breakdance\Fonts\FontsController;
use Yabe\Webfont\Admin\AdminPage;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Cache;
use Yabe\Webfont\Utils\Font;

/**
 * Breakdance integration.
 *
 * @author Joshua Gugun Siagian <suabahasa@gmail.com>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        if (defined('__BREAKDANCE_VERSION')) {
            // 1.2.1 is the last breakpoint version that supports the old font registration method
            if (version_compare(__BREAKDANCE_VERSION, '1.2.1', '>')) {
                remove_action('breakdance_register_fonts', '\Breakdance\GoogleFontsPlugin\loadGoogleFonts');
                add_action('breakdance_register_fonts', fn (FontsController $fontsController) => $this->register_fonts($fontsController), -1_000_001);
            } else {
                remove_action('init', '\Breakdance\GoogleFontsPlugin\loadGoogleFonts');
                add_action('breakdance_loaded', fn () => $this->register_fonts_v10201(), 1_000_001);
            }
        }

        add_action('admin_menu', static fn () => AdminPage::add_redirect_submenu_page('breakdance'), 1_000_001);
    }

    public function get_name(): string
    {
        return 'breakdance';
    }

    public function register_fonts(FontsController $fontsController)
    {
        $fonts = Font::get_fonts();

        foreach ($fonts as $font) {
            $cssName = Font::css_variable($font['family']);
            $dropdownLabel = sprintf('[Yabe] %s', $font['title']);
            $fallbackString = $font['fallback_family'] ?? '';
            $dependencies = [
                'styles' => [
                    // Cache::get_cache_url(Cache::CSS_CACHE_FILE),
                ],
            ];

            $previewImageUrl = null;

            if ($font['type'] === 'google-fonts') {
                $previewImageUrl = sprintf(
                    'https://cdn.jsdelivr.net/gh/khoben/gfont-previews/output/previews/%s-regular.png',
                    str_replace(' ', '%20', $font['family'])
                );
            }

            $fontsController->registerFont(
                Font::slugify($font['family']),
                $cssName,
                $dropdownLabel,
                $fallbackString,
                $dependencies,
                $previewImageUrl
            );
        }
    }

    public function register_fonts_v10201()
    {
        $fonts = Font::get_fonts();

        foreach ($fonts as $font) {
            $cssName = Font::css_variable($font['family']);
            $dropdownLabel = sprintf('[Yabe] %s', $font['title']);
            $fallbackString = $font['fallback_family'] ?? '';
            $dependencies = [
                'styles' => [
                    // Cache::get_cache_url(Cache::CSS_CACHE_FILE),
                ],
            ];

            \Breakdance\Fonts\registerFont(
                Font::slugify($font['family']),
                $cssName,
                $dropdownLabel,
                $fallbackString,
                $dependencies
            );
        }
    }
}
