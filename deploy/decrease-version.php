<?php

/**
 * @var string Working directory.
 */
define('WORK_DIR', getcwd());

/**
 * src/Plugin.php
 * 
 * the pattern is `public const MAJOR_VERSION = X;`, where X is the major version.
 * then decrease it by 1.
 */
function step_1()
{
    $curr_file = file_get_contents(WORK_DIR . '/src/Plugin.php');
    preg_match('/public const MAJOR_VERSION = (\d+);/', $curr_file, $matches);
    $major_version = $matches[1];
    $curr_file = preg_replace('/public const MAJOR_VERSION = (\d+);/', 'public const MAJOR_VERSION = ' . ($major_version - 1) . ';', $curr_file);
    file_put_contents(WORK_DIR . '/src/Plugin.php', $curr_file);
}


/**
 * yabe-webfont.php
 * 
 * the pattern is `* Version:             X.Y.Z`, where X is the major version, Y is the minor version and Z is the patch version.
 */
function step_2()
{
    $curr_file = file_get_contents(WORK_DIR . '/yabe-webfont.php');
    preg_match('/\* Version:             (\d+)\.(\d+)\.(\d+)/', $curr_file, $matches);
    $major_version = $matches[1];
    $minor_version = $matches[2];
    $patch_version = $matches[3];
    $curr_file = preg_replace('/\* Version:             (\d+)\.(\d+)\.(\d+)/', '* Version:             ' . ($major_version - 1) . '.' . $minor_version . '.' . $patch_version, $curr_file);
    file_put_contents(WORK_DIR . '/yabe-webfont.php', $curr_file);
}

/**
 * readme.txt
 * 
 * the pattern is `Stable tag: X.Y.Z`, where X is the major version, Y is the minor version and Z is the patch version.
 */
function step_3()
{
    $curr_file = file_get_contents(WORK_DIR . '/readme.txt');
    preg_match('/Stable tag: (\d+)\.(\d+)\.(\d+)/', $curr_file, $matches);
    $major_version = $matches[1];
    $minor_version = $matches[2];
    $patch_version = $matches[3];
    $curr_file = preg_replace('/Stable tag: (\d+)\.(\d+)\.(\d+)/', 'Stable tag: ' . ($major_version - 1) . '.' . $minor_version . '.' . $patch_version, $curr_file);
    file_put_contents(WORK_DIR . '/readme.txt', $curr_file);
}


/**
 * readme.txt
 * 
 * the pattern is `= X.Y.Z =`, where X is the major version, Y is the minor version and Z is the patch version.
 * it occurs several times in the file but with different version.
 * decreace each X by 1.
 */
function step_4()
{
    $curr_file = file_get_contents(WORK_DIR . '/readme.txt');
    preg_match_all('/= (\d+)\.(\d+)\.(\d+) =/', $curr_file, $matches);
    $curr_file = preg_replace_callback('/= (\d+)\.(\d+)\.(\d+) =/', function ($matches) {
        return '= ' . ($matches[1] - 1) . '.' . $matches[2] . '.' . $matches[3] . ' =';
    }, $curr_file);
    file_put_contents(WORK_DIR . '/readme.txt', $curr_file);
}

/**
 * On the src/Plugin.php file, the pattern is: `public const VERSION = 'X.Y.Z';`, where X is the major version, Y is the minor version and Z is the patch version. Decrease X by 1.
 */
function step_5()
{
    $curr_file = file_get_contents(WORK_DIR . '/src/Plugin.php');
    preg_match('/public const VERSION = \'(\d+)\.(\d+)\.(\d+)\';/', $curr_file, $matches);
    $major_version = $matches[1];
    $minor_version = $matches[2];
    $patch_version = $matches[3];
    $curr_file = preg_replace('/public const VERSION = \'(\d+)\.(\d+)\.(\d+)\';/', 'public const VERSION = \'' . ($major_version - 1) . '.' . $minor_version . '.' . $patch_version . '\';', $curr_file);
    file_put_contents(WORK_DIR . '/src/Plugin.php', $curr_file);
}

/**
 * On the src/Plugin.php file, the pattern is: `public const VERSION_ID = W;`, Where W is integer, do reduce W by 10000
 */
function step_6()
{
    $curr_file = file_get_contents(WORK_DIR . '/src/Plugin.php');
    preg_match('/public const VERSION_ID = (\d+);/', $curr_file, $matches);
    $version_id = $matches[1];
    $curr_file = preg_replace('/public const VERSION_ID = (\d+);/', 'public const VERSION_ID = ' . ($version_id - 10000) . ';', $curr_file);
    file_put_contents(WORK_DIR . '/src/Plugin.php', $curr_file);
}

step_1();
step_2();
step_3();
step_4();
step_5();
step_6();