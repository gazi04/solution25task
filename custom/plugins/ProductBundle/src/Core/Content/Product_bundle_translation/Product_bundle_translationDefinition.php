<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle_translation;

use ProductBundle\Core\Content\Product_bundle\Product_bundleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

class Product_bundle_translationDefinition extends EntityTranslationDefinition
{
  public const ENTITY_NAME = 'product_bundle_translation';

  public function getEntityName(): string
  {
    return self::ENTITY_NAME;
  }

  public function getParentDefinitionClass(): string
  {
    return Product_bundleDefinition::class;
  }

  public function getEntityClass(): string
  {
    return Product_bundle_translationEntity::class;
  }

  public function getCollectionClass(): string
  {
    return Product_bundle_translationCollection::class;
  }

  protected function defineFields(): FieldCollection
  {
    return new FieldCollection([
      (new StringField('title', 'title'))->addFlags(new Required()),
    ]);
  }
}
