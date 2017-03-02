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
 *   id = "dcamp_landing_about_community_block",
 *   admin_label = @Translation("DrupalCamp block for landing about the community")
 * )
 */
class DcampAboutCommunityBlock extends DcampBlockBase{
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $form['link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text for link'),
      '#default_value' => isset($config['link_text']) ? $config['link_text'] : '',
    ];
    $form['link_url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL for link'),
      '#default_value' => isset($config['link_url']) ? $config['link_url']: '',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $this->setConfigurationValue('link_text', $form_state->getValue('link_text'));
    $this->setConfigurationValue('link_url', $form_state->getValue('link_url'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::build();
    $config = $this->getConfiguration();
    if(!empty($config['link_text']) && !empty($config['link_url'])){
      try {
        $url = Url::fromUri($config['link_url'],['attributes' => ['class' => ['button']]]);
        $link = \Drupal::linkGenerator()->generate($config['link_text'],$url);
        $build['#link'] = $link;
      } catch (\Exception $e){
        \Drupal::logger()->error($e->getMessage());
      }
    }
    return $build;
  }
}
