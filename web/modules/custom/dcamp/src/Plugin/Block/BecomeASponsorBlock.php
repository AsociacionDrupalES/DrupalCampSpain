<?php

namespace Drupal\dcamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'BecomeASponsorBlock' block.
 *
 * @Block(
 *  id = "become_a_sponsor_block",
 *  admin_label = @Translation("Become a Sponsor Block"),
 * )
 */
class BecomeASponsorBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['become_a_sponsor_block']['#markup'] = 'Implement BecomeASponsorBlock.';

    return $build;
  }

}
