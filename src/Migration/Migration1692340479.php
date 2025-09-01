<?php declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1692340479 extends MigrationStep
{
    use InheritanceUpdaterTrait;

    public function getCreationTimestamp(): int
    {
        return 1692340479;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
CREATE TABLE IF NOT EXISTS `alpha_popup_products` (
  `popup_id` BINARY(16) NOT NULL,
  `product_id` BINARY(16) NOT NULL,
  `product_version_id` BINARY(16) NOT NULL,
  `created_at` DATETIME(3) NULL,
  `updated_at` DATETIME(3) NULL,
  PRIMARY KEY (`popup_id`, `product_id`),
  CONSTRAINT `fk.alpha_popup_products.product_id` FOREIGN KEY (`product_id`)
    REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk.alpha_popup_products.popup_id` FOREIGN KEY (`popup_id`)
    REFERENCES `alpha_popup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE  
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_unicode_ci;
');
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `navigation_active` tinyint(1) DEFAULT 1 NOT NULL'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `product_active` tinyint(1) DEFAULT 0 NOT NULL'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `login_active` tinyint(1) DEFAULT 0 NOT NULL'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `register_active` tinyint(1) DEFAULT 0 NOT NULL'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `checkout_active` tinyint(1) DEFAULT 0 NOT NULL'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `checkout_finish_active` tinyint(1) DEFAULT 1 NOT NULL'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `checkout_confirm_active` tinyint(1) DEFAULT 1 NOT NULL'
        );
        try {
            $this->updateInheritance($connection, 'product', 'alpha_popups');
        } catch (\Exception) {
        }

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}


