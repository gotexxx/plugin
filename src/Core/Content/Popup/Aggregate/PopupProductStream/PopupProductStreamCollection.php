<?php
declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupProductStream;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                             add(PopupProductStreamEntity $entity)
 * @method void                             set(string $key, PopupProductStreamEntity $entity)
 * @method PopupProductStreamEntity[]    getIterator()
 * @method PopupProductStreamEntity[]    getElements()
 * @method PopupProductStreamEntity|null get(string $key)
 * @method PopupProductStreamEntity|null first()
 * @method PopupProductStreamEntity|null last()
 */
class PopupProductStreamCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return PopupProductStreamEntity::class;
    }
}
