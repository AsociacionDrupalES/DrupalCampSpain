<?php
/**
 * @file
 * Contains...
 */

namespace Drupal\dcamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Routing\RouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a Description block with Countdown
 *
 * @Block(
 *   id = "dcamp_landing_info_with_countdown_block",
 *   admin_label = @Translation("Landing Info With Count Down Block")
 * )
 */
class DcampLandingInfoWithCountDownBlock extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface{


  /**
   * @var RouteMatch
   */
  protected $currentRouteMatch;

  /**
   * Constructs a new Node Type object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   *
   * @todo Add the countdown
   */
  public function build() {
    $config = $this->getConfiguration();
    $dcamp = $this->currentRouteMatch->getParameter('dcamp');
    return [
      '#theme' => 'dcamp_landing_info_with_countdown',
      '#title' => $config['title'],
      '#body' => $config['body'],
      '#countdown' => '@todo countdown to '. $dcamp->get('starting_date'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form =  parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['title'],
      '#format' => $config['title'],
      '#required' => TRUE,
    ];
    $form['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body'),
      '#default_value' => $config['body'],
      '#format' => $config['body'],
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
