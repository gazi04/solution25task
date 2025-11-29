<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle_assigned_products;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void add(Product_bundle_assigned_productsEntity $entity)
 * @method void set(string $key, Product_bundle_assigned_productsEntity $entity)
 * @method Product_bundle_assigned_productsEntity[] getIterator()
 * @method Product_bundle_assigned_productsEntity[] getElements()
 * @method Product_bundle_assigned_productsEntity|null get(string $key)
 * @method Product_bundle_assigned_productsEntity|null first()
 * @method Product_bundle_assigned_productsEntity|null last()
 */
class Product_bundle_assigned_productsCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return Product_bundle_assigned_productsEntity::class;
    }
}
