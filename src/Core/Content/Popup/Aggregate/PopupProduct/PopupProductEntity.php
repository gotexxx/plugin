<?php
declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupProduct;

use Alpha\Popup\Core\Content\Popup\PopupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class PopupProductEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $popupId;



    /**
     * @var string
     */
    protected $productVersionId;


    public function getPopupId(): string
    {
        return $this->popupId;
    }

    public function setPopupId(string $popupId): void
    {
        $this->popupId = $popupId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductVersionId(): string
    {
        return $this->productVersionId;
    }

    public function setProductVersionId(string $productVersionId): void
    {
        $this->productVersionId = $productVersionId;
    }

}
