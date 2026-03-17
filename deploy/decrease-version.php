<?php

declare(strict_types=1);

define('WORK_DIR', dirname(__DIR__));

function decrement_major_version(string $version): string
{
    if (!preg_match('/^(\d+)\.(\d+)\.(\d+)$/', $version, $matches)) {
        throw new RuntimeException(sprintf('Invalid version string: %s', $version));
    }

    $major = (int) $matches[1];
    $minor = $matches[2];
    $patch = $matches[3];

    if ($major < 1) {
        throw new RuntimeException(sprintf('Cannot decrease major version for: %s', $version));
    }

    return sprintf('%d.%s.%s', $major - 1, $minor, $patch);
}

function update_file(string $relativePath, string $pattern, callable $callback, bool $requireMatch = true): void
{
    $path = WORK_DIR . '/' . $relativePath;
    $content = file_get_contents($path);

    if ($content === false) {
        throw new RuntimeException(sprintf('Unable to read file: %s', $relativePath));
    }

    $count = 0;
    $updated = preg_replace_callback($pattern, $callback, $content, -1, $count);

    if ($updated === null) {
        throw new RuntimeException(sprintf('Regex error while updating: %s', $relativePath));
    }

    if ($requireMatch && $count === 0) {
        throw new RuntimeException(sprintf('Pattern not found in file: %s', $relativePath));
    }

    if (file_put_contents($path, $updated) === false) {
        throw new RuntimeException(sprintf('Unable to write file: %s', $relativePath));
    }
}

update_file(
    'constant.php',
    "/public const VERSION = '(\d+\.\d+\.\d+)';/",
    static function (array $matches): string {
        return "public const VERSION = '" . decrement_major_version($matches[1]) . "';";
    }
);

update_file(
    'yabe-webfont.php',
    '/\* Version:\s+(\d+\.\d+\.\d+)/',
    static function (array $matches): string {
        return '* Version:             ' . decrement_major_version($matches[1]);
    }
);

update_file(
    'readme.txt',
    '/Stable tag: (\d+\.\d+\.\d+)/',
    static function (array $matches): string {
        return 'Stable tag: ' . decrement_major_version($matches[1]);
    }
);

update_file(
    'readme.txt',
    '/= (\d+\.\d+\.\d+) - ([0-9-]+) =/',
    static function (array $matches): string {
        return '= ' . decrement_major_version($matches[1]) . ' - ' . $matches[2] . ' =';
    }
);

update_file(
    'readme.txt',
    '/= (\d+\.\d+\.\d+) =/',
    static function (array $matches): string {
        return '= ' . decrement_major_version($matches[1]) . ' =';
    },
    false
);
