<?php declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1740151243 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1740151243;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup` ADD 
                `js_events` varchar(255);
                ALTER TABLE `alpha_popup` ADD
                `element_selectors` varchar(255);'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}