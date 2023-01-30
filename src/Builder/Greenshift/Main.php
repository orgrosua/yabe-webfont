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

namespace Yabe\Webfont\Builder\Greenshift;

use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;

/**
 * Greenshift integration.
 *
 * @author Joshua <id@rosua.org>
 */
class Main implements BuilderInterface
{
    public function __construct()
    {
        add_filter('gspb_local_font_array', fn ($localfonts) => $this->filter_gspb_local_font_array($localfonts), 1_000_001);
    }

    public function get_name(): string
    {
        return 'greenshift';
    }

    public function filter_gspb_local_font_array($localfonts)
    {
        $localfonts = (! empty($localfont)) ? json_decode($localfont, true) : [];

        $font_families = Runtime::get_font_families();

        foreach ($font_families as $font_family) {
            $localfonts[$font_family['family']] = [];
        }

        return json_encode($localfonts);
    }
}
