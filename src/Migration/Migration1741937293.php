<?php

declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1741937293 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1741937293;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement("alter table alpha_popup modify cms_page_id binary(16) null;");
        $connection->executeStatement("alter table alpha_popup alter column popup_width set default 'medium';");
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}