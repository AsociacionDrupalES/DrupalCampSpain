<?php

namespace Drupal\dcamp_sessions;

use Drupal\dcamp_sessions\Entity\Session;
use Google_Client;
use Google_Service_Sheets;

class SessionProposalsService {

  const SATURDAY = 'Saturday';
  const FRIDAY = 'Friday';
  const POLIVALENTE = 'Polivalente (45)';
  const AUDITORIO = 'Auditorio (170)';
  const CINE = 'Cine (75)';

  /**
   * The list of selected sessions.
   *
   * @var array
   */
  protected $selectedSessions = [
    '/sessions/contribuir-drupal-por-donde-comenzar-de-0-100-regiguren' => [
      self::SATURDAY,
      '16:30',
      self::POLIVALENTE,
    ],
    '/sessions/el-poder-de-webform-antes-yamform-regiguren' => [
      self::SATURDAY,
      '11:30',
      self::POLIVALENTE,
    ],
    '/sessions/responsive-images-under-control-chumillas' => [
      self::SATURDAY,
      '11:30',
      self::AUDITORIO,
    ],
    '/sessions/todo-lo-que-hay-que-saber-para-enfrentarte-al-marketing-automation-cocinaitaliana' => [
      self::SATURDAY,
      '09:00',
      self::CINE,
    ],
    '/sessions/headless-drupal-gizra-way-davidbaltha' => [
      self::SATURDAY,
      '12:30',
      self::AUDITORIO,
    ],
    '/sessions/test-driven-development-drupal-8-nuezweb' => [
      self::FRIDAY,
      '16:00',
      self::CINE,
    ],
    '/sessions/casos-de-exito-de-drupal-en-espana' => [
      self::FRIDAY,
      '17:00',
      self::CINE,
    ],
    '/sessions/adventures-apis-justafish' => [
      self::SATURDAY,
      '10:00',
      self::AUDITORIO,
    ],
    '/sessions/improve-your-testing-experience-migrate-default-content-module-marinero' => [
      self::SATURDAY,
      '17:30',
      self::CINE,
    ],
    '/sessions/el-grupo-de-trabajo-de-documentacion-en-espanol-te-necesita-skuark' => [
      self::SATURDAY,
      '15:30',
      self::CINE,
    ],
    '/sessions/using-traefikio-your-local-docker-environments-jsbalsera' => [
      self::FRIDAY,
      '15:00',
      self::AUDITORIO,
    ],
    '/sessions/seleccionando-un-cms-para-la-transformacion-por-que-drupal-javierespadas' => [
      self::FRIDAY,
      '16:00',
      self::AUDITORIO,
    ],
    '/sessions/caching-post-data-varnish-rodricels' => [
      self::FRIDAY,
      '15:00',
      self::AUDITORIO,
    ],
    '/sessions/pretendiendo-ser-rockstar-developers-juanolalla' => [
      self::SATURDAY,
      '15:30',
      self::AUDITORIO,
    ],
    '/sessions/extreme-page-composition-paragraphs-sidddi' => [
      self::SATURDAY,
      '10:00',
      self::CINE,
    ],
    '/sessions/lets-encrypt-o-como-tener-tu-pagina-https-tras-5-instrucciones-en-la-linea-de-comandos' => [
      self::FRIDAY,
      '15:00',
      self::CINE,
    ],
    '/sessions/pierdele-el-miedo-drupal-console-kikoalonsob' => [
      self::SATURDAY,
      '15:30',
      self::CINE,
    ],
    '/sessions/iniciacion-al-profiling-con-webprofiler-kikoalonsob' => [
      self::SATURDAY,
      '12:30',
      self::POLIVALENTE,
    ],
    '/sessions/abstraer-requisitos-de-cliente-en-modulos-para-contribuir' => [
      self::FRIDAY,
      '15:00',
      self::POLIVALENTE,
    ],
    '/sessions/buenas-practicas-front-end-maquetacion-por-componentes' => [
      self::FRIDAY,
      '10:00',
      self::CINE,
    ],
    '/sessions/creacion-de-extensiones-de-twig-para-personalizar-la-presentacion-de-campos-vlledo' => [
      self::FRIDAY,
      '15:00',
      self::CINE,
    ],
    '/sessions/google-amp-y-drupal-8-antoniogarrod' => [
      self::FRIDAY,
      '15:00',
      self::POLIVALENTE,
    ],
    '/sessions/tu-drupal-esta-listo-lo-sabe-google-beagonpoz' => [
      self::FRIDAY,
      '16:00',
      self::POLIVALENTE,
    ],
    '/sessions/search-api-solr-en-drupal-8-bases-practicas-para-encontrar-lo-buscado-tuwebo' => [
      self::SATURDAY,
      '11:30',
      self::CINE,
    ],
    '/sessions/commerce-en-drupal-8-facine' => [
      self::FRIDAY,
      '11:00',
      self::CINE,
    ],
    '/sessions/why-drupal-davidbaltha' => [
      self::FRIDAY,
      '15:00',
      self::POLIVALENTE,
    ],
    '/sessions/entidades-en-drupal-8-luisortizramos' => [
      self::FRIDAY,
      '17:00',
      self::POLIVALENTE,
    ],
    '/sessions/migrando-datos-drupal-8-jonhattan' => [
      self::FRIDAY,
      '12:00',
      self::AUDITORIO,
    ],
    '/sessions/pruebas-de-carga-web-con-gatling-jonhattan' => [
      self::SATURDAY,
      '15:30',
      self::CINE,
    ],
    '/sessions/bdd-desarrollo-guiado-por-comportamiento-jltutor' => [
      self::SATURDAY,
      '09:00',
      self::AUDITORIO,
    ],
    '/sessions/drupal-8s-multilingual-apis-building-entire-world-penyaskito' => [
      self::FRIDAY,
      '11:00',
      self::AUDITORIO,
    ],
    '/sessions/drupal-instantaneo-con-service-workers-asilgag' => [
      self::SATURDAY,
      '09:00',
      self::POLIVALENTE,
    ],
    '/sessions/introduccion-al-modulo-ui-patterns-usando-atomic-ui-components-en-drupal8-nicolasbottini' => [
      self::SATURDAY,
      '16:30',
      self::CINE,
    ],
    '/sessions/grandes-proyectos-desde-la-perspectiva-de-una-pequena-empresa-ii-como-retenerlos-rvilar' => [
      self::SATURDAY,
      '10:00',
      self::POLIVALENTE,
    ],
    '/sessions/css-grid-layout-aless86' => [
      self::FRIDAY,
      '15:00',
      self::CINE,
    ],
    '/sessions/putting-yourself-out-there-and-avoiding-what-ifs-mikeherchel' => [
      self::FRIDAY,
      '10:00',
      self::AUDITORIO,
    ],
    '/sessions/debugging-profiling-rocking-out-browser-based-developer-tools-mikeherchel' => [
      self::SATURDAY,
      '17:30',
      self::AUDITORIO,
    ],
    '/sessions/scrum-master-story-hackathons-scrum-agile-and-chicken-nancyvb' => [
      self::SATURDAY,
      '16:30',
      self::AUDITORIO,
    ],
    '/sessions/drupal-8-cache-developers-jjcarrion' => [
      self::FRIDAY,
      '17:00',
      self::AUDITORIO,
    ],
    '/sessions/synchronize-your-drupal-data-carto-plopesc' => [
      self::FRIDAY,
      '15:00',
      self::AUDITORIO,
    ],
    '/sessions/exploring-composer-drupal-8-drupal-project-salvabg' => [
      self::FRIDAY,
      '12:00',
      self::CINE,
    ],
    '/sessions/patrones-de-diseno-inclusivos-como-abordar-la-accesibilidad-en-el-desarrollo-de-temas-personalizados' => [
      self::SATURDAY,
      '12:30',
      self::CINE,
    ],
    '/sessions/empresas-comunidades-opensource-el-cliente-siempre-gana' => [
      self::SATURDAY,
      '17:30',
      self::POLIVALENTE,
    ],
    '/sessions/taller-agile-jugando-con-pos-it-y-gomets-martinrayo' => [
      self::SATURDAY,
      '15:30',
      self::POLIVALENTE,
    ],
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
    $selected_sessions = [];

    foreach ($sessions as $session) {
      if (in_array($session->getUrl(), array_keys($this->selectedSessions))) {
        $this->setSessionScheduling($session);
        $selected_sessions[] = $session;
      }
    }

    return $selected_sessions;
  }

  /**
   * Adds scheduling details to a session.
   *
   * @param \Drupal\dcamp_sessions\Entity\Session $session
   *   The session to update.
   */
  private function setSessionScheduling(Session $session) {
    if (empty($this->selectedSessions[$session->getUrl()])) {
      return;
    }
    $scheduling_data = $this->selectedSessions[$session->getUrl()];
    $session->setDay($scheduling_data[0]);
    $session->setTimeSlot($scheduling_data[1]);
    $session->setRoomName($scheduling_data[2]);
  }

}