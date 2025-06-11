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

/**
 * Plugin constants.
 *
 * @since 2.0.0
 */
class YABE_WEBFONT
{
    /**
     * @var string
     */
    public const FILE = __DIR__ . '/yabe-webfont.php';

    /**
     * @var string
     */
    public const VERSION = '2.0.75';

    /**
     * @var string
     */
    public const WP_OPTION = 'yabe_webfont';

    /**
     * @var string
     */
    public const DB_TABLE_PREFIX = 'yabe_webfont';

    /**
     * The text domain should use the literal string 'yabe-webfont' as the text domain.
     * This constant is used for reference only and should not be used as the actual text domain.
     * 
     * @var string
     */
    public const TEXT_DOMAIN = 'yabe-webfont';

    /**
     * @var array
     */
    public const EDD_STORE = [
        'store_url' => 'https://rosua.org',
        'item_id' => 18,
        'author' => 'idrosua',
    ];

    /**
     * @var string
     */
    public const REST_NAMESPACE = 'yabe-webfont/v1';

    /**
     * @var string
     */
    public const HOSTED_WAKUFONT = 'https://wakufont-hosted.rosua.org';
    
    /**
     * @var string
     */
    public const PLUGIN_URI = 'https://webfont.yabe.land';

    /**
     * @var 
     */
    public const USER_AGENTS = [
        'WOFF2' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:43.0) Gecko/20100101 Firefox/43.0',
        'WOFF' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:38.0) Gecko/20100101 Firefox/38.0',
        'TTF' => 'Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/538.1 (KHTML, like Gecko) Safari/538.1 Daum/4.1',
        // 'CURRENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/115.0',
        'CURRENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0',
    ];
}
