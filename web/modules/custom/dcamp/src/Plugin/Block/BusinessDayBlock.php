<?php

namespace Drupal\dcamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'BusinessDayBlock' block.
 *
 * @Block(
 *  id = "business_day_block",
 *  admin_label = @Translation("Business Day Block"),
 * )
 */
class BusinessDayBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['business_day_block']['#markup'] = 'Implement BusinessDayBlock.';

    return $build;
  }

}
