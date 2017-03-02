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
class DcampStayUpdatedBlock extends NewsletterBlock implements ContainerFactoryPluginInterface{

}
