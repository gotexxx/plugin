<?php declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1575386258Popup extends MigrationStep
{
    use InheritanceUpdaterTrait;

    public function getCreationTimestamp(): int
    {
        return 1_575_386_258;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `alpha_popup` (
              `id` BINARY(16) NOT NULL,
              `name` VARCHAR(255) NOT NULL,
              `cms_page_id` BINARY(16) NOT NULL,
              `active` tinyint(1) DEFAULT 0 NOT NULL,
              `cookie_expire_days` FLOAT NOT NULL DEFAULT 0,
              `popup_width` varchar(255) DEFAULT "1200px" NULL,
              `customer_restriction` tinyint(1) DEFAULT 0 NOT NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL,
              `valid_from` DATETIME(3) NULL,
              `valid_until` DATETIME(3) NULL,
              PRIMARY KEY (`id`),
              KEY `fk.alpha_popup.cms_page_id` (`cms_page_id`),
              CONSTRAINT `fk.alpha_popup.cms_page_id` FOREIGN KEY (`cms_page_id`)
              REFERENCES `cms_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `alpha_popup_sales_channel` (
              `id` BINARY(16) NOT NULL,
              `popup_id` BINARY(16) NOT NULL,
              `sales_channel_id` BINARY(16) NOT NULL,
              `priority` INT NOT NULL DEFAULT 0,
              `created_at` DATETIME(3) NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`id`),
              INDEX `idx.popup_sales_channel.sales_channel_id` (`sales_channel_id` ASC),
              INDEX `idx.popup_sales_channel.popup_id` (`popup_id` ASC),
              CONSTRAINT `fk.popup_sales_channel.popup_id` FOREIGN KEY (`popup_id`)
                REFERENCES `alpha_popup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.popup_sales_channel.sales_channel_id` FOREIGN KEY (`sales_channel_id`)
                REFERENCES `sales_channel` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `alpha_popup_categories` (
              `popup_id` BINARY(16) NOT NULL,
              `category_id` BINARY(16) NOT NULL,
              `category_version_id` BINARY(16) NOT NULL,
              `created_at` DATETIME(3) NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`popup_id`, `category_id`, `category_version_id`),
              CONSTRAINT `fk.alpha_popup_categories.product_id` FOREIGN KEY (`popup_id`)
                REFERENCES `alpha_popup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.alpha_popup_categories.category_id` FOREIGN KEY (`category_id`, `category_version_id`)
                REFERENCES `category` (`id`, `version_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `alpha_popup_persona_customer` (
                `popup_id` BINARY(16) NOT NULL,
                `customer_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`popup_id`, `customer_id`),
                CONSTRAINT `fk.alpha_popup_persona_customer.popup_id` FOREIGN KEY (`popup_id`)
                  REFERENCES `alpha_popup` (id) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.alpha_popup_persona_customer.customer_id` FOREIGN KEY (`customer_id`)
                  REFERENCES `customer` (id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
       ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `alpha_popup_persona_rule` (
                `popup_id` BINARY(16) NOT NULL,
                `rule_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`popup_id`, `rule_id`),
                CONSTRAINT `fk.alpha_popup_persona_rule.popup_id` FOREIGN KEY (`popup_id`)
                  REFERENCES `alpha_popup` (id) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.alpha_popup_persona_rule.rule_id` FOREIGN KEY (`rule_id`)
                  REFERENCES `rule` (id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
       ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `alpha_popup_order_rule` (
                `popup_id` BINARY(16) NOT NULL,
                `rule_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`popup_id`, `rule_id`),
                CONSTRAINT `fk.alpha_popup_order_rule.popup_id` FOREIGN KEY (`popup_id`)
                  REFERENCES `alpha_popup` (id) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.alpha_popup_order_rule.rule_id` FOREIGN KEY (`rule_id`)
                  REFERENCES `rule` (id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
       ');
        try {
            $this->updateInheritance($connection, 'category', 'alpha_popups');
            $this->updateInheritance($connection, 'rule', 'alpha_popups');
            $this->updateInheritance($connection, 'customer', 'alpha_popups');
        }catch (\Exception){
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
