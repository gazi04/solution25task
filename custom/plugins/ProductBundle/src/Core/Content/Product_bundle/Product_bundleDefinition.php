<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle;

use ProductBundle\Core\Content\Product_bundle_assigned_products\Product_bundle_assigned_productsDefinition;
use ProductBundle\Core\Content\Product_bundle_translation\Product_bundle_translationDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;

class Product_bundleDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'product_bundle';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return Product_bundleEntity::class;
    }

    public function getCollectionClass(): string
    {
        return Product_bundleCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new Required()),
            (new TranslatedField('title')),
            (new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id')),
            (new OneToManyAssociationField('assignedProducts', Product_bundle_assigned_productsDefinition::class, 'product_bundle_id')),
            (new TranslationsAssociationField(Product_bundle_translationDefinition::class, 'product_bundle_id')),
        ]);
    }
}
