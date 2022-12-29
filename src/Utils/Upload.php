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

namespace Yabe\Webfont\Utils;

use function current_user_can;
use function wp_check_filetype;

/**
 * Upload utility functions for the plugin.
 *
 * @author Joshua <joshua@rosua.org>
 */
class Upload
{
    /**
     * Add the font mime types to the allowed upload mimes.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face#description
     * @see https://developer.wordpress.org/reference/hooks/upload_mimes/
     */
    public static function upload_mimes(array $mimes, bool $manual_upload = false): array
    {
        if (
            !$manual_upload
            && (!current_user_can('manage_options') || !isset($_POST['yabe_webfont_font_upload']))
        ) {
            return $mimes;
        }

        $exts = [
            'woff2' => 'font/woff2',
            'woff' => 'font/woff',
            'ttf' => 'font/ttf',
            'otf' => 'font/otf',
            'eot' => 'font/eot',
        ];
        
        foreach ($exts as $ext => $ext_mime) {
            if (!isset($mimes[$ext])) {
                $mimes[$ext] = $ext_mime;
            }
        }
        
        return $mimes;
    }

    /**
     * Disable real MIME check (introduced in WordPress 4.7.1)
     *
     * @see https://wordpress.stackexchange.com/a/252296/44794
     * @see https://developer.wordpress.org/reference/hooks/wp_check_filetype_and_ext/
     */
    public static function disable_real_mime_check(array $data, string $file, string $filename, $mimes)
    {
        $filetype = wp_check_filetype($filename, $mimes);

        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename']
        ];
    }
}
