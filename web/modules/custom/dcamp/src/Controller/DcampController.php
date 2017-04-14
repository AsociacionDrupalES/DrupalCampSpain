<?php
/**
 * @file
 * @todo add info.
 */


namespace Drupal\dcamp\Controller;

use Drupal\block_content\BlockContentViewBuilder;
use Drupal\block_content\Entity\BlockContent;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Render\Renderer;
use Drupal\dcamp\Entity\Dcamp;


/**
 * Class DcampController
 *
 * @package Drupal\dcamp\Controller
 */
class DcampController extends ControllerBase {

  /**
   * Controller for the landing page.
   *
   * @param \Drupal\dcamp\Entity\Dcamp $dcamp
   * @return array
   */
  public function landing(Dcamp $dcamp) {
    // Return nothing. We only use blocks on the landing pages.
    return [
      '#markup' => '',
    ];
  }

  /**
   * Controller for the frontpage.
   *
   * @todo Convert Service Container to Dependency Injection.
   */
  public function frontpage() {
    /** @var BlockManager $blockManager */
    $blockManager = \Drupal::getContainer()->get('plugin.manager.block');

    return [
      '#theme' => 'frontpage',
      '#business_day' => $blockManager->createInstance('block_content:dcamp_2017_business_day')->build(),
      '#venue' => $blockManager->createInstance('block_content:dcamp_2017_venue')->build(),
      '#sponsors' => views_embed_view('sponsors', 'all_sponsors'),
      '#become_a_sponsor' => $blockManager->createInstance('block_content:dcamp_2017_become_a_sponsor')->build(),
      '#speakers' => views_embed_view('featured_speakers', 'block'),
      '#community' => $blockManager->createInstance('block_content:dcamp_2017_community')->build(),
      '#attached' => [
        'library' => [
          'dcamp_2017_theme/frontpage',
          'dcamp_2017_landing_theme/plax'
        ]
      ]
    ];
  }

  /**
   * Title callback for frontpage
   */

  public function frontpageTitle(){
    return $this->t('Two days of Drupal | Madrid May 5-6');
  }

}