<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle_translation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void add(Product_bundle_translationEntity $entity)
 * @method void set(string $key, Product_bundle_translationEntity $entity)
 * @method Product_bundle_translationEntity[] getIterator()
 * @method Product_bundle_translationEntity[] getElements()
 * @method Product_bundle_translationEntity|null get(string $key)
 * @method Product_bundle_translationEntity|null first()
 * @method Product_bundle_translationEntity|null last()
 */
class Product_bundle_translationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return Product_bundle_translationEntity::class;
    }
}
