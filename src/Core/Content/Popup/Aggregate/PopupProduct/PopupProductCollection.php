<?php
declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupProduct;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                             add(PopupProductEntity $entity)
 * @method void                             set(string $key, PopupProductEntity $entity)
 * @method PopupProductEntity[]    getIterator()
 * @method PopupProductEntity[]    getElements()
 * @method PopupProductEntity|null get(string $key)
 * @method PopupProductEntity|null first()
 * @method PopupProductEntity|null last()
 */
class PopupProductCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return PopupProductEntity::class;
    }
}
