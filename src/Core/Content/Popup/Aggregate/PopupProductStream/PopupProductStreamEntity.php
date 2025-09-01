<?php
declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupProductStream;

use Alpha\Popup\Core\Content\Popup\PopupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class PopupProductStreamEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $popupId;



    /**
     * @var string
     */
    protected $productStreamVersionId;


    public function getPopupId(): string
    {
        return $this->popupId;
    }

    public function setPopupId(string $popupId): void
    {
        $this->popupId = $popupId;
    }

    public function getProductStreamId(): string
    {
        return $this->productStreamId;
    }

    public function setProductStreamId(string $productStreamId): void
    {
        $this->productStreamId = $productStreamId;
    }

    public function getProductStreamVersionId(): string
    {
        return $this->productStreamVersionId;
    }

    public function setProductStreamVersionId(string $productStreamVersionId): void
    {
        $this->productStreamVersionId = $productStreamVersionId;
    }

}
