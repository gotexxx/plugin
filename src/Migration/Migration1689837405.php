<?php declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;

class Migration1689837405 extends MigrationStep
{
    use InheritanceUpdaterTrait;
    public function getCreationTimestamp(): int
    {
        return 1689837405;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `alpha_popup_cart_rule` (
                `popup_id` BINARY(16) NOT NULL,
                `rule_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`popup_id`, `rule_id`),
                CONSTRAINT `fk.alpha_popup_cart_rule.popup_id` FOREIGN KEY (`popup_id`)
                  REFERENCES `alpha_popup` (id) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.alpha_popup_cart_rule.rule_id` FOREIGN KEY (`rule_id`)
                  REFERENCES `rule` (id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
       ');
        $this->updateInheritance($connection, 'cart', 'alpha_popups');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive

    }
}
