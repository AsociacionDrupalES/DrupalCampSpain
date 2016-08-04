<?php
/**
 * @file
 * Contains \Drupal\dcamp\Form\DcampForm.
 */

namespace Drupal\dcamp\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\Element\Datetime;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Extension\ThemeHandler;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DcampForm.
 *
 * @package Drupal\dcamp\Form
 */
class DcampForm extends EntityForm {

  /**
   * Theme Manager
   *
   * @var ThemeHandler
   */
  protected $themeHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(ThemeHandler $theme_handler) {
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $dcamp = $this->entity;

    $themes_list = $this->themeHandler->listInfo();
    $themes_options = array_map(function ($theme_info) {
      return $theme_info->info['name'];
    }, $themes_list);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $dcamp->label(),
      '#description' => $this->t("Label for the DrupalCamp Configuration."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $dcamp->id(),
      '#machine_name' => [
        'exists' => '\Drupal\dcamp\Entity\Dcamp::load',
      ],
      '#disabled' => !$dcamp->isNew(),
    ];

    $form['landing_theme'] = [
      '#title' => t('Landing Theme'),
      '#description' => t('Select the theme that should be used for rendering the landing page.'),
      '#type' => 'select',
      '#options' => $themes_options,
      '#default_value' => $dcamp->get('landing_theme'),
      '#required' => TRUE,
    ];

    $form['starting_date'] = [
      '#title' => t('Starting Date'),
      '#description' => t('Starting date of the Drupal Camp'),
      '#type' => 'datetime',
      '#default_value' => $dcamp->get('starting_date') ? DrupalDateTime::createFromTimestamp($dcamp->get('starting_date')) : '',
      '#element_validate' => [[$this, 'validateStartingDate']],
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * Validate Starting date
   */
  public function validateStartingDate($element, FormStateInterface $form_state){
    /** @var DrupalDateTime $dateTime */
    $datetime = $element['#value']['object'];
    $form_state->setValueForElement($element, $datetime->getTimestamp());
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $dcamp = $this->entity;
    $status = $dcamp->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label DrupalCamp Configuration.', [
          '%label' => $dcamp->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DrupalCamp Configuration.', [
          '%label' => $dcamp->label(),
        ]));
    }
    $form_state->setRedirectUrl($dcamp->urlInfo('collection'));
  }

}
