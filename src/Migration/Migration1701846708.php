<?php declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;

class Migration1701846708 extends MigrationStep
{
    use InheritanceUpdaterTrait;
    public function getCreationTimestamp(): int
    {
        return 1701846708;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `delay_show_time` int DEFAULT 500 NOT NULL'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `scroll_activation_percentage` int DEFAULT 0 NOT NULL'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive

    }
}
