<?php
declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupSalesChannel;

use Alpha\Popup\Core\Content\Popup\PopupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class PopupSalesChannelEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $popupId;

    /**
     * @var string
     */
    protected $salesChannelId;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var PopupEntity|null
     */
    protected $popup;

    /**
     * @var SalesChannelEntity|null
     */
    protected $salesChannel;

    public function getPopupId(): string
    {
        return $this->popupId;
    }

    public function setPopupId(string $popupId): void
    {
        $this->popupId = $popupId;
    }

    public function getSalesChannelId(): string
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId(string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getPopup(): ?PopupEntity
    {
        return $this->popup;
    }

    public function setPopup(PopupEntity $popup): void
    {
        $this->popup = $popup;
    }

    public function getSalesChannel(): ?SalesChannelEntity
    {
        return $this->salesChannel;
    }

    public function setSalesChannel(SalesChannelEntity $salesChannel): void
    {
        $this->salesChannel = $salesChannel;
    }
}
