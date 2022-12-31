<?php

declare(strict_types=1);

namespace Yabe\Webfont\Migrations;

use Rosua\Migrations\AbstractMigration;
use wpdb;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221121114557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        /** @var wpdb $wpdb */
        global $wpdb;

        $sql = "CREATE TABLE `{$wpdb->prefix}yabe_webfont_fonts` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `type` VARCHAR(50) NOT NULL DEFAULT 'custom' COMMENT 'Type of font (custom, google)',
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `family` VARCHAR(255),
            `metadata` TEXT,
            `files` TEXT,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `status` INT(1) NOT NULL DEFAULT 0 COMMENT 'Status of published font (1 = published, 0 = draft)',
            PRIMARY KEY (`id`)
        ) {$this->collation()}";

        dbDelta($sql);
    }

    public function down(): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        /** @var wpdb $wpdb */
        global $wpdb;

        $sql = "DROP TABLE IF EXISTS `{$wpdb->prefix}yabe_webfont_fonts`";

        $wpdb->query($sql);
    }
}
