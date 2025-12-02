<?php declare(strict_types=1);

namespace ProductBundle\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
class Migration1764671700remove_id_from_bundle_translation_table extends MigrationStep
{
  public function getCreationTimestamp(): int
  {
    return 1764671700;
  }

  public function update(Connection $connection): void
  {

    $connection->executeStatement('
      ALTER TABLE `product_bundle_translation`
      DROP COLUMN IF EXISTS `id`
    ');
  }
}
