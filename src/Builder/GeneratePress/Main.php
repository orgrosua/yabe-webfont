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

namespace Yabe\Webfont\Builder\GeneratePress;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * GeneratePress integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        /**
         * @see https://github.com/tomusborne/generatepress/blob/e7fbf5693bfe4325a41cae988e3eda16550d4025/inc/defaults.php#L412
         */
        add_filter('generate_typography_default_fonts', static fn ($fonts) => array_merge($fonts, array_column(Runtime::get_font_families(), 'family')), 100001);
    }

    public function get_name(): string
    {
        return 'generate-press';
    }
}
