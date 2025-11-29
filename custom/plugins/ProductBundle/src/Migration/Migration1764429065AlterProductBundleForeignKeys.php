<?php declare(strict_types=1);

namespace ProductBundle\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1764429065AlterProductBundleForeignKeys extends MigrationStep
{
  public function getCreationTimestamp(): int
  {
    return 1764429065;
  }

  public function update(Connection $connection): void
  {
    $this->updateProductBundleTable($connection);
    $this->updateProductBundleTranslationTable($connection);
    $this->updateProductBundleAssignedProductsTable($connection);
  }

  private function updateProductBundleTable(Connection $connection): void
  {
    $connection->executeStatement('
      ALTER TABLE `product_bundle`
      DROP COLUMN IF EXISTS `name`,
      DROP COLUMN IF EXISTS `description`,
      DROP COLUMN IF EXISTS `active`;
    ');

    $connection->executeStatement('
      ALTER TABLE `product_bundle`
      ADD COLUMN IF NOT EXISTS `product_id` BINARY(16) NOT NULL;
    ');

    /* Drop foreign keys */
    $this->safeDropForeignKey($connection, "product_bundle", "fk.product_bundle.product_id");

    $connection->executeStatement('
      ALTER TABLE `product_bundle`
      ADD CONSTRAINT `fk.product_bundle.product_id` FOREIGN KEY (`product_id`)
      REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ');
  }

  private function updateProductBundleTranslationTable(Connection $connection): void
  {
    // Drop old constraints
    $connection->executeStatement('
      ALTER TABLE `product_bundle_translation`
      DROP COLUMN IF EXISTS `name`,
      DROP COLUMN IF EXISTS `description`,
      DROP COLUMN IF EXISTS `active`;
    ');

    $connection->executeStatement('
      ALTER TABLE `product_bundle_translation`
      ADD COLUMN IF NOT EXISTS `product_bundle_id` BINARY(16) NOT NULL,
      ADD COLUMN IF NOT EXISTS `language_id` BINARY(16) NOT NULL,
      ADD COLUMN IF NOT EXISTS `title` VARCHAR(255) NULL;
    ');

    $this->safeDropForeignKey($connection, "product_bundle_translation", "fk.product_bundle_translation.product_bundle_id");
    $this->safeDropForeignKey($connection, "product_bundle_translation", "fk.product_bundle_translation.language_id");

    $connection->executeStatement('
      ALTER TABLE `product_bundle_translation`
      ADD CONSTRAINT `fk.product_bundle_translation.product_bundle_id` FOREIGN KEY (`product_bundle_id`)
      REFERENCES `product_bundle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ');
    $connection->executeStatement('
      ALTER TABLE `product_bundle_translation`
      ADD CONSTRAINT `fk.product_bundle_translation.language_id` FOREIGN KEY (`language_id`)
      REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ');
  }

  private function updateProductBundleAssignedProductsTable(Connection $connection): void
  {
    $connection->executeStatement('
      ALTER TABLE `product_bundle_assigned_products`
      DROP COLUMN IF EXISTS `name`,
      DROP COLUMN IF EXISTS `description`,
      DROP COLUMN IF EXISTS `active`;
    ');

    $connection->executeStatement('
      ALTER TABLE `product_bundle_assigned_products`
      ADD COLUMN IF NOT EXISTS `product_bundle_id` BINARY(16) NOT NULL,
      ADD COLUMN IF NOT EXISTS `product_id` BINARY(16) NOT NULL,
      ADD COLUMN IF NOT EXISTS `quantity` INT(11) NOT NULL DEFAULT 1;
    ');

    /* Drop foreign keys */
    $this->safeDropForeignKey($connection, "product_bundle_assigned_products", "fk.product_bundle_assigned_products.bundle_id");
    $this->safeDropForeignKey($connection, "product_bundle_assigned_products", "fk.product_bundle_assigned_products.product_id");

    $connection->executeStatement('
      ALTER TABLE `product_bundle_assigned_products`
      ADD CONSTRAINT `fk.product_bundle_assigned_products.bundle_id` FOREIGN KEY (`product_bundle_id`)
      REFERENCES `product_bundle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ');
    $connection->executeStatement('
      ALTER TABLE `product_bundle_assigned_products`
      ADD CONSTRAINT `fk.product_bundle_assigned_products.product_id` FOREIGN KEY (`product_id`)
      REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ');
  }

  private function safeDropForeignKey(Connection $connection, string $tableName, string $constraintName): void
  {
    // 1. Check if the constraint exists in the information_schema
    $sql = "SELECT 1 FROM information_schema.table_constraints 
    WHERE constraint_schema = DATABASE() 
    AND table_name = :tableName 
    AND constraint_name = :constraintName 
    AND constraint_type = 'FOREIGN KEY'";

    $exists = $connection->fetchOne($sql, [
      'tableName' => $tableName, 
      'constraintName' => $constraintName
    ]);

    // 2. Only execute the DROP command if the key exists.
    if ($exists) {
      $connection->executeStatement(
        'ALTER TABLE `' . $tableName . '` DROP FOREIGN KEY `' . $constraintName . '`'
      );
    }
  }
  public function updateDestructive(Connection $connection): void
  {
    // No destructive action is required for this migration.
  }
}
