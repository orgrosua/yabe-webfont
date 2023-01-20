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
use Yabe\Webfont\Core\Runtime;

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
        add_filter('bricks/builder/standard_fonts', fn ($fonts) => array_merge($fonts, Runtime::get_fonts_family()));
    }
}
