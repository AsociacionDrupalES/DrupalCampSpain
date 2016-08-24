<?php
/**
 * @file
 * Contains...
 */

namespace Drupal\dcamp\Plugin\Block;


use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a Description block with Countdown
 *
 * @Block(
 *   id = "dcamp_landing_about_sponsor_block",
 *   admin_label = @Translation("DrupalCamp block for landing about sponsoring")
 * )
 */
class DcampLandingAboutSponsorBlock extends DcampLandingBlockBase{

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $form['download_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text for download button'),
      '#default_value' => isset($config['download_text']) ? $config['download_text'] : '',
    ];
    $form['download_url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL to PDF Download'),
      '#default_value' => isset($config['download_link']) ? $config['download_link']: '',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $this->setConfigurationValue('download_text', $form_state->getValue('download_text'));
    $this->setConfigurationValue('download_url', $form_state->getValue('download_url'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::build();
    $config = $this->getConfiguration();
    if(!empty($config['download_text']) && !empty($config['download_url'])){
      try {
        $url = Url::fromUri($config['download_url'], ['attributes' => ['class' => ['button']]]);
        $link = \Drupal::linkGenerator()->generate($config['download_text'],$url);
        $build['#link'] = $link;
      } catch (\Exception $e){
        \Drupal::logger()->error($e->getMessage());
      }
    }
    return $build;
  }

}
