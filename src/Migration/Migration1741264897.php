<?php

declare(strict_types=1);

namespace Alpha\Popup\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1741264897 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1741264897;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
CREATE TABLE IF NOT EXISTS `alpha_popup_product_streams` (
  `popup_id` BINARY(16) NOT NULL,
  `product_stream_id` BINARY(16) NOT NULL,
  `product_stream_version_id` BINARY(16) NOT NULL,
  `created_at` DATETIME(3) NULL,
  `updated_at` DATETIME(3) NULL,
  PRIMARY KEY (`popup_id`, `product_stream_id`),
  CONSTRAINT `fk.alpha_popup_product_streams.product_stream_id` FOREIGN KEY (`product_stream_id`)
    REFERENCES `product_stream` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk.alpha_popup_product_streams.popup_id` FOREIGN KEY (`popup_id`)
    REFERENCES `alpha_popup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE  
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_unicode_ci;
');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}