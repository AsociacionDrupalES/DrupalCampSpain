<?php

namespace Drupal\dcamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'VenueBlock' block.
 *
 * @Block(
 *  id = "venue_block",
 *  admin_label = @Translation("Venue Block"),
 * )
 */
class VenueBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['venue_block']['#markup'] = 'Implement VenueBlock.';

    return $build;
  }

}
