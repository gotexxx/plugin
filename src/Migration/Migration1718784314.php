<?php declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1718784314 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1718784314;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `popup_max_width` varchar(255)'
        );
        $connection->executeStatement(
            'ALTER TABLE `alpha_popup`
    ADD `popup_width_individual` varchar(255)'
        );
    }
}
