<?php declare(strict_types=1);

namespace ProductBundle\Core\Content\Product_bundle_assigned_products;

use ProductBundle\Core\Content\Product_bundle\Product_bundleEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class Product_bundle_assigned_productsEntity extends Entity
{
  use EntityIdTrait;

  protected ?string $productBundleId = null;
  protected ?string $productId = null;
  protected int $quantity = 1;
  protected ?Product_bundleEntity $productBundle = null;
  protected ?ProductEntity $product = null;

  public function getProductBundleId(): ?string
  {
    return $this->productBundleId;
  }

  public function setProductBundleId(string $productBundleId): void
  {
    $this->productBundleId = $productBundleId;
  }

  public function getProductId(): ?string
  {
    return $this->productId;
  }

  public function setProductId(string $productId): void
  {
    $this->productId = $productId;
  }

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): void
  {
    $this->quantity = $quantity;
  }

  public function getProductBundle(): ?Product_bundleEntity
  {
    return $this->productBundle;
  }

  public function setProductBundle(Product_bundleEntity $productBundle): void
  {
    $this->productBundle = $productBundle;
  }

  public function getProduct(): ?ProductEntity
  {
    return $this->product;
  }

  public function setProduct(ProductEntity $product): void
  {
    $this->product = $product;
  }}
