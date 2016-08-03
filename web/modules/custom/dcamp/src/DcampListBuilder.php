<?php

/**
 * @file
 * Contains \Drupal\dcamp\DcampListBuilder.
 */

namespace Drupal\dcamp;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of DrupalCamp Configuration entities.
 */
class DcampListBuilder extends ConfigEntityListBuilder {
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('DrupalCamp Configuration');
    $header['id'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /**
     * @var DcampInterface $entity
     */
    $row['label'] = $entity->toLink();
    $row['id'] = $entity->id();
    // You probably want a few more properties here...
    return $row + parent::buildRow($entity);
  }

}
