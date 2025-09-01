<?php
declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupSalesChannel;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                             add(PopupSalesChannelEntity $entity)
 * @method void                             set(string $key, PopupSalesChannelEntity $entity)
 * @method PopupSalesChannelEntity[]    getIterator()
 * @method PopupSalesChannelEntity[]    getElements()
 * @method PopupSalesChannelEntity|null get(string $key)
 * @method PopupSalesChannelEntity|null first()
 * @method PopupSalesChannelEntity|null last()
 */
class PopupSalesChannelCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return PopupSalesChannelEntity::class;
    }
}
