<?php

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/yabe-webfont.php', __DIR__ . '/src']);

    $ecsConfig->sets([SetList::PSR_12, SetList::COMMON, SetList::CLEAN_CODE]);
};
