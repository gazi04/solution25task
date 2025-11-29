<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle_translation;

use ProductBundle\Core\Content\Product_bundle\Product_bundleEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class Product_bundle_translationEntity extends Entity
{
  use EntityIdTrait;

  protected ?string $productBundleId = null;
  protected ?string $title = null;
  protected ?Product_bundleEntity $productBundle = null;

  public function getProductBundleId(): ?string
  {
    return $this->productBundleId;
  }

  public function setProductBundleId(string $productBundleId): void
  {
    $this->productBundleId = $productBundleId;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(?string $title): void
  {
    $this->title = $title;
  }

  public function getProductBundle(): ?Product_bundleEntity
  {
    return $this->productBundle;
  }

  public function setProductBundle(Product_bundleEntity $productBundle): void
  {
    $this->productBundle = $productBundle;
  }
}
