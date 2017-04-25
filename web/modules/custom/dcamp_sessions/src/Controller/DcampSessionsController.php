<?php

namespace Drupal\dcamp_sessions\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\dcamp_sessions\Entity\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DcampSessionsController extends ControllerBase {

  /**
   * The amount of seconds to cache session listings.
   *
   * @var int
   */
  protected $maxAge = 120;

  protected $selectedSessions = [
    '/sessions/contribuir-drupal-por-donde-comenzar-de-0-100-regiguren',
    '/sessions/el-poder-de-webform-antes-yamform-regiguren',
    '/sessions/responsive-images-under-control-chumillas',
    '/sessions/todo-lo-que-hay-que-saber-para-enfrentarte-al-marketing-automation-cocinaitaliana',
    '/sessions/headless-drupal-gizra-way-davidbaltha',
    '/sessions/test-driven-development-drupal-8-nuezweb',
    '/sessions/casos-de-exito-de-drupal-en-espana',
    '/sessions/adventures-apis-justafish',
    '/sessions/improve-your-testing-experience-migrate-default-content-module-marinero',
    '/sessions/el-grupo-de-trabajo-de-documentacion-en-espanol-te-necesita-skuark',
    '/sessions/using-traefikio-your-local-docker-environments-jsbalsera',
    '/sessions/seleccionando-un-cms-para-la-transformacion-por-que-drupal-javierespadas',
    '/sessions/caching-post-data-varnish-rodricels',
    '/sessions/pretendiendo-ser-rockstar-developers-juanolalla',
    '/sessions/extreme-page-composition-paragraphs-sidddi',
    '/sessions/lets-encrypt-o-como-tener-tu-pagina-https-tras-5-instrucciones-en-la-linea-de-comandos',
    '/sessions/pierdele-el-miedo-drupal-console-kikoalonsob',
    '/sessions/iniciacion-al-profiling-con-webprofiler-kikoalonsob',
    '/sessions/abstraer-requisitos-de-cliente-en-modulos-para-contribuir',
    '/sessions/buenas-practicas-front-end-maquetacion-por-componentes',
    '/sessions/creacion-de-extensiones-de-twig-para-personalizar-la-presentacion-de-campos-vlledo',
    '/sessions/google-amp-y-drupal-8-antoniogarrod',
    '/sessions/tu-drupal-esta-listo-lo-sabe-google-beagonpoz',
    '/sessions/search-api-solr-en-drupal-8-bases-practicas-para-encontrar-lo-buscado-tuwebo',
    '/sessions/commerce-en-drupal-8-facine',
    '/sessions/why-drupal-davidbaltha',
    '/sessions/entidades-en-drupal-8-luisortizramos',
    '/sessions/migrando-datos-drupal-8-jonhattan',
    '/sessions/pruebas-de-carga-web-con-gatling-jonhattan',
    '/sessions/bdd-desarrollo-guiado-por-comportamiento-jltutor',
    '/sessions/drupal-8s-multilingual-apis-building-entire-world-penyaskito',
    '/sessions/drupal-instantaneo-con-service-workers-asilgag',
    '/sessions/introduccion-al-modulo-ui-patterns-usando-atomic-ui-components-en-drupal8-nicolasbottini',
    '/sessions/grandes-proyectos-desde-la-perspectiva-de-una-pequena-empresa-ii-como-retenerlos-rvilar',
    '/sessions/css-grid-layout-aless86',
    '/sessions/putting-yourself-out-there-and-avoiding-what-ifs-mikeherchel',
    '/sessions/debugging-profiling-rocking-out-browser-based-developer-tools-mikeherchel',
    '/sessions/scrum-master-story-hackathons-scrum-agile-and-chicken-nancyvb',
    '/sessions/drupal-8-cache-developers-jjcarrion',
    '/sessions/synchronize-your-drupal-data-carto-plopesc',
    '/sessions/explotando-composer-en-drupal-8-con-drupal-project-salvabg',
    '/sessions/patrones-de-diseno-inclusivos-como-abordar-la-accesibilidad-en-el-desarrollo-de-temas-personalizados',
  ];

  /**
   * Lists proposed sessions
   *
   * @param string $session_type
   *   The type of session to show. Defaults to proposed sessions.
   *
   * @return mixed
   *   JsonResponse when requested via API request. A render array
   *   otherwise.
   */
  public function listSessions($session_type) {
    $sessions = \Drupal::service('dcamp_sessions.proposals')->getProposals();

    // Filter out selected sessions if needed.
    if ($session_type == 'selected') {
      $sessions = array_values(array_filter($sessions, function($session) {
        return in_array($session->getUrl(), $this->selectedSessions);
      }));
    }

    // Check if this is an API request.
    if (\Drupal::request()->query->get('_format') == 'json') {
      // Prepare and send response.
      $headers = [
        'max-age' => $this->maxAge,
      ];
      return new JsonResponse($sessions, Response::HTTP_OK, $headers);
    }

    $list_items = [];
    foreach ($sessions as $session) {
      $list_items[] = [
        '#markup' => '<h2 class="session__title--list"><a href="' . $session->getUrl() . '">' . Xss::filter($session->getTitle()) . '</a></h2><div class="session__author">'. Xss::filter($session->getName()) . '</div>',
      ];
    }

    return [
      '#theme' => 'proposed_sessions',
      '#title' => $this->getSessionsTitle($session_type),
      '#items' => $list_items,
      '#cache' => [
        'max-age' => $this->maxAge,
      ],
    ];
  }

  /**
   * Returns the sessions listing title.
   *
   * Also does rudimentary validation, since we could not figure out
   * how to use the Choices route param constraint.
   *
   * @param string $session_type
   *   The type of session to show. Defaults to proposed sessions.
   */
  public function getSessionsTitle($session_type) {
    $title = '';

    if ($session_type == 'proposed') {
      $title = 'Proposed sessions';
    }
    elseif ($session_type == 'selected') {
      $title = 'Selected sessions';
    }

    return $title;
  }

  /**
   * View session details
   *
   * @param int $submission_id
   *   The identifier of the submission id, which maps to the row
   *   in the spreadsheet.
   *
   * @return mixed
   *   JsonResponse when requested via API request. A render array
   *   otherwise.
   */
  public function view($submission_id) {
    $submission_id = (int) $submission_id;
    $sessions = \Drupal::service('dcamp_sessions.proposals')->getProposals();
    if (empty($sessions[$submission_id])) {
      throw new BadRequestHttpException(t('Invalid submission id. https://media4.giphy.com/media/uOAXDA7ZeJJzW/giphy.gif'));
    }

    // Extract session details.
    $session = $sessions[$submission_id];

    // Check if this is an API request.
    if (\Drupal::request()->query->get('_format') == 'json') {
      // Prepare and send response.
      $headers = [
        'max-age' => $this->maxAge,
      ];
      return new JsonResponse($session, Response::HTTP_OK, $headers);
    }

    $build = [
      '#theme' => 'proposed_session',
      '#session' => $session,
      '#cache' => [
        'max-age' => $this->maxAge,
      ],
    ];

    return $build;
  }

}
