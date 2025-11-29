<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle;

use ProductBundle\Core\Content\Product_bundle_assigned_products\Product_bundle_assigned_productsCollection;
use ProductBundle\Core\Content\Product_bundle_translation\Product_bundle_translationCollection;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class Product_bundleEntity extends Entity
{
  use EntityIdTrait;

  protected ?string $productId = null;
  protected ?ProductEntity $product = null;
  protected ?string $title = null;
  protected ?Product_bundle_assigned_productsCollection $assignedProducts = null;
  protected ?Product_bundle_translationCollection $translations = null;

  public function getProductId(): ?string
  {
    return $this->productId;
  }

  public function setProductId(string $productId): void
  {
    $this->productId = $productId;
  }

  public function getProduct(): ?ProductEntity
  {
    return $this->product;
  }

  public function setProduct(ProductEntity $product): void
  {
    $this->product = $product;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(?string $title): void
  {
    $this->title = $title;
  }

  public function getAssignedProducts(): ?Product_bundle_assigned_productsCollection
  {
    return $this->assignedProducts;
  }

  public function setAssignedProducts(Product_bundle_assigned_productsCollection $assignedProducts): void
  {
    $this->assignedProducts = $assignedProducts;
  }

  public function getTranslations(): ?Product_bundle_translationCollection
  {
    return $this->translations;
  }

  public function setTranslations(Product_bundle_translationCollection $translations): void
  {
    $this->translations = $translations;
  }
}
