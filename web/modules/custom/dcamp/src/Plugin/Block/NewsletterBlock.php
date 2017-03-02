<?php
/**
 * @file
 * Contains...
 */

namespace Drupal\dcamp\Plugin\Block;


use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\mailchimp_signup\Form\MailchimpSignupPageForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Description block with Countdown
 *
 * @Block(
 *   id = "dcamp_newsletter_block",
 *   admin_label = @Translation("Newsletter block")
 * )
 */
class NewsletterBlock extends DcampBlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var QueryFactory $queryFactory
   */
  protected $queryFactory;

  /**
   * Constructs a new Node Type object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, QueryFactory $query_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->queryFactory = $query_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::build();
    $mailchimp_config = $this->queryFactory->get('mailchimp_signup')->execute();

    // We assume there is only one mailchimp signup configuration entity.

    if (empty($mailchimp_config)) {
      drupal_set_message($this->t('Please configure a Mailchimp service'));
      return $build;
    }

    $signup_id = reset($mailchimp_config);
    $signup = mailchimp_signup_load($signup_id);

    $form = new MailchimpSignupPageForm();

    $form_id = 'mailchimp_signup_subscribe_block_' . $signup->id . '_form';
    $form->setFormID($form_id);
    $form->setSignup($signup);

    $form_array = \Drupal::formBuilder()->getForm($form);

    // Tweak the form.
    $form_array['mergevars']['EMAIL']['#title'] = '';
    $form_array['mergevars']['EMAIL']['#attributes']['placeholder'] = $this->t('Your email');
    $form_array['#suffix'] = '<div class="note">' . $this->t("Don't worry, we won't spam you.") . '</div>';
    $build['#form'] = $form_array;
    return $build;
  }

}
