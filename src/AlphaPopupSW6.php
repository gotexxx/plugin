<?php declare(strict_types=1);

namespace Alpha\Popup;

use Doctrine\DBAL\Connection;
use setasign\Fpdi\PdfParser\Type\PdfName;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;


class AlphaPopupSW6 extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        $cmsPageService = $this->container->get(CmsPageTypeService::class);
    }

    public function activate(ActivateContext $activateContext): void
    {
        $registry = $this->container->get(EntityIndexerRegistry::class);
        if (!$registry) {
            return;
        }
        $registry->sendIndexingMessage(['category.indexer']);
        $registry->sendIndexingMessage(['rule.indexer']);
        $registry->sendIndexingMessage(['customer.indexer']);


        $connection = $this->container->get(Connection::class);
        $connection->executeUpdate("UPDATE cms_page SET type = 'alpha_popup' WHERE type = 'page' AND JSON_EXTRACT(config, '$.alpha_popup_type_backup') = true;");
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        $this->changeCMSPageTypes();
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);
        $this->changeCMSPageTypes();
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);

        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_cart_rule`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_categories`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_order_rule`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_persona_customer`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_persona_rule`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_product_streams`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_products`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `alpha_popup_sales_channel`');


        try {
            $connection->executeUpdate('ALTER TABLE `category` DROP COLUMN `alpha_popups`');
            $connection->executeUpdate('ALTER TABLE `rule` DROP COLUMN `alpha_popups`');
            $connection->executeUpdate('ALTER TABLE `customer` DROP COLUMN `alpha_popups`');
            $connection->executeUpdate('ALTER TABLE `product` DROP COLUMN `alpha_popups`');
            $connection->executeUpdate('ALTER TABLE `cart` DROP COLUMN `alpha_popups`');
        } catch (\Exception) {
        }
    }

    private function changeCMSPageTypes()
    {
        $connection = $this->container->get(Connection::class);
        $connection->executeUpdate("UPDATE cms_page SET config = JSON_SET(IFNULL(config,'{}' ), '$.alpha_popup_type_backup',true) WHERE type = 'alpha_popup';");
        $connection->executeUpdate("UPDATE cms_page SET type = 'page' WHERE type = 'alpha_popup';");
    }
}
