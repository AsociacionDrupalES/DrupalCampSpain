<?php
/**
 * @file
 * Contains...
 */

namespace Drupal\dcamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a Base block definition for Landing bocks.
 */
abstract class DcampBlockBase extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    return [
      '#theme' => 'landing_block',
      '#title' => $config['title'],
      '#body' => $config['body'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => isset($config['title']) ? $config['title'] : '',
      '#required' => TRUE,
    ];
    $form['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body'),
      '#default_value' => isset($config['body']) ? $config['body'] : '',
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('title', $form_state->getValue('title'));
    $this->setConfigurationValue('body', $form_state->getValue('body'));
  }
}
