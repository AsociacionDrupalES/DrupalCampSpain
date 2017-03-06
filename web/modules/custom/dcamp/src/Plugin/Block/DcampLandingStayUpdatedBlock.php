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
 *   id = "dcamp_landing_stay_updated_block",
 *   admin_label = @Translation("DrupalCamp block for landing for staying updated")
 * )
 */
class DcampLandingStayUpdatedBlock extends NewsletterBlock implements ContainerFactoryPluginInterface{

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::build();

    $form_array = $build['#form'];
    // Tweak the form.
    $form_array['#prefix'] = '<h3>' . $this->t('Subscribe to the newsletter') .'</h3>';
    $form_array['mergevars']['EMAIL']['#title'] = '';
    $form_array['mergevars']['EMAIL']['#attributes']['placeholder'] = $this->t('Your email');
    $form_array['#suffix'] = '<div class="note">' . $this->t("Don't worry, we won't spam you.") .'</div>';
    $build['#form'] = $form_array;
    return $build;
  }

}
