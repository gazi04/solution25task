<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product;

use ProductBundle\Core\Content\Product_bundle\Product_bundleDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ProductExtension extends EntityExtension
{
  public function getEntityName(): string
  {
    return ProductDefinition::ENTITY_NAME;
  }

  public function extendFields(FieldCollection $collection): void
  {
    $collection->add(
      (new OneToOneAssociationField(
        'productBundle',          // The property name we use in Admin (extensions.productBundle)
        'id',                     // Product ID (Local)
        'product_id',             // Bundle's foreign key (Reference)
        Product_bundleDefinition::class,
        true                      // Autoload: Load bundle data automatically with product
      ))->addFlags(new CascadeDelete())
    );
  }

  public function getDefinitionClass(): string
  {
    return ProductDefinition::class;
  }
}
