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

namespace Yabe\Webfont;

use Rosua\Migrations\Migrator;

/**
 * Manage the plugin custom database tables.
 *
 * @author Joshua <id@rosua.org>
 */
final class Migration
{
    private Migrator $migrator;

    public function __construct()
    {
        $this->migrator = Migrator::getInstance([
            'tableName' => 'yabe_webfont_migrations',
            'namespace' => 'Yabe\Webfont\Migrations',
            'directory' => 'migrations',
            'basePath' => dirname(YABE_WEBFONT_FILE),
            'commandNamespace' => 'yabe-webfont migrations',
        ]);

        add_action('a!yabe/webfont/plugins:activate_plugin_start', fn () => $this->install());
        add_action('a!yabe/webfont/plugins:upgrade_plugin_start', fn () => $this->upgrade());

        $this->migrator->boot();
    }

    public function install()
    {
        $this->migrator->install();
        $this->migrator->execute();
    }

    public function upgrade()
    {
        $this->migrator->install();
        $this->migrator->execute();
    }
}
