<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle_assigned_products;

use ProductBundle\Core\Content\Product_bundle\Product_bundleDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;

class Product_bundle_assigned_productsDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'product_bundle_assigned_products';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return Product_bundle_assigned_productsEntity::class;
    }

    public function getCollectionClass(): string
    {
        return Product_bundle_assigned_productsCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('product_bundle_id', 'productBundleId', Product_bundleDefinition::class))->addFlags(new Required()),
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new Required()),
            (new IntField('quantity', 'quantity'))->addFlags(new Required()),
            (new ManyToOneAssociationField('productBundle', 'product_bundle_id', Product_bundleDefinition::class, 'id')),
            (new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id')),
        ]);
    }
}
