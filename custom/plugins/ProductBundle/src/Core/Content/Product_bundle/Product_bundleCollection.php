<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void add(Product_bundleEntity $entity)
 * @method void set(string $key, Product_bundleEntity $entity)
 * @method Product_bundleEntity[] getIterator()
 * @method Product_bundleEntity[] getElements()
 * @method Product_bundleEntity|null get(string $key)
 * @method Product_bundleEntity|null first()
 * @method Product_bundleEntity|null last()
 */
class Product_bundleCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return Product_bundleEntity::class;
    }
}
