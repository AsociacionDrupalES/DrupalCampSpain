<?php

namespace Drupal\dcamp_sessions;

use Drupal\dcamp_sessions\Entity\Session;
use Google_Client;
use Google_Service_Sheets;

class SessionProposalsService {

  /**
   * The list of selected sessions.
   *
   * @var array
   */
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
    '/sessions/exploring-composer-drupal-8-drupal-project-salvabg',
    '/sessions/patrones-de-diseno-inclusivos-como-abordar-la-accesibilidad-en-el-desarrollo-de-temas-personalizados',
    '/sessions/empresas-comunidades-opensource-el-cliente-siempre-gana',
  ];

  /**
   * Returns the spreadsheet values.
   *
   * @return \Drupal\dcamp_sessions\Entity\Session[]
   *   The array of session proposals.
   *
   * @throws \RuntimeException
   *   If there is no credentials file to authenticate against Google.
   */
  public function getProposals() {
    $config = \Drupal::config('dcamp_sessions.settings');
    /** @var \Drupal\Core\Path\AliasStorage $aliasStorage */
    $aliasStorage = \Drupal::service('path.alias_storage');
    $pathAutoAliasCleaner = \Drupal::service('pathauto.alias_cleaner');
    $sessions = [];

    // First check if we are in developer mode.
    if ($config->get('debugging')) {
      $path = \Drupal::service('module_handler')->getModule('dcamp_sessions')->getPath();
      $sessions_raw = file_get_contents($path . '/fixtures/sessions.json');
      $sessions_json = json_decode($sessions_raw);
    }
    else {
      if (empty($config->get('service_account_file'))) {
        throw new \RuntimeException('The path of the service account file has not been set.');
      }
      if (empty($config->get('spreadsheet_id'))) {
        throw new \RuntimeException('The identifier of the spreadsheet has not been set.');
      }
      if (empty($config->get('spreadsheet_range'))) {
        throw new \RuntimeException('The range of the spreadsheet has not been set.');
      }
      putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $config->get('service_account_file'));
      $client = new Google_Client();
      $client->useApplicationDefaultCredentials();
      $client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);
      $service = new Google_Service_Sheets($client);

      $result = $service->spreadsheets_values->get($config->get('spreadsheet_id'), $config->get('spreadsheet_range'));

      // Skip the first row from the spreadsheet values as it contains heading titles.
      $sessions_json = array_slice($result->getValues(), 1);
    }

    // Turn raw session proposals into Session objects.
    foreach ($sessions_json as $key => $session_json) {
      $session = new Session($session_json);

      // Check if we need to create an alias.
      $alias = '/sessions/' . $pathAutoAliasCleaner->cleanString($session->getTitle() . '-' . $session->getTwitter());
      if (!$aliasStorage->load(['alias' => $alias])) {
        $aliasStorage->save('/sessions/' . $key, $alias);
      }
      $session->setUrl($alias);
      $sessions[] = $session;
    }

    return $sessions;
  }

  /**
   * Filters out the list of selected sessions.
   *
   * @return \Drupal\dcamp_sessions\Entity\Session[]
   *   The array of selected sessions.
   */
  public function getSelected() {
    $sessions = $this->getProposals();
    $selected_sessions = array_values(array_filter($sessions, function($session) {
      return in_array($session->getUrl(), $this->selectedSessions);
    }));
    return $selected_sessions;
  }

}