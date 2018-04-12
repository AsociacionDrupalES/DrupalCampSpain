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
    // Once sessions have been selected, edit this array and the above constants accordingly.
    // Once the schedule has completed, complete the information about sessions.
    // Template:
    //    '/sessions/contribuir-drupal-por-donde-comenzar-de-0-100-regiguren' => [
    //      self::SATURDAY,
    //      '16:30',
    //      self::POLIVALENTE,
    //    ],

    '/sessions/integrando-componentes-en-la-edicion-de-contenidos-chumillas' => [],
    '/sessions/symfony-framework-style-api-building-drupal' => [],
    '/sessions/headless-drupal-rteijeiro' => [],
    '/sessions/construyendo-un-motor-de-recomendaciones-con-d8-y-solr-estoyausente' => [],
    '/sessions/masticando-requisitos-como-hacer-mas-facil-la-vida-al-desarrollador-y-al-cliente-rvilar' => [],
    '/sessions/programacion-reactiva-lidiando-con-la-asincronia-sanzante' => [],
    '/sessions/docker-subete-al-barco-de-las-herramientas-de-desarrollo-estandar-marinero' => [],
    '/sessions/csi-autopsia-de-vulnerabilidades-rabbitlair' => [],
    '/sessions/elasticsearch-101-isholgueras' => [],
    '/sessions/e2e-testing-con-nightwatchjs-salvabg' => [],
    '/sessions/seo-page-orientado-drupal-buenas-practicas-para-ponerselo-facil-google-asierlc' => [],
    '/sessions/casos-de-exito-de-drupal-en-espana-davidgilbiko2' => [],
    '/sessions/consejos-y-trucos-para-cualificar-una-oportunidad-drupal-es-ese-proyecto-para-nosotros-o-mejor-que' => [],
    '/sessions/designer-vs-front-end-do-you-know-way-nesta' => [],
    '/sessions/utilizando-drupal-como-lms-xapi-learning-record-store-martinrayo' => [],
    '/sessions/contribuir-drupal-por-donde-comenzar-de-0-100-regiguren' => [],
    '/sessions/el-trabajo-en-remoto-desde-diferentes-puntos-de-vista' => [],
    '/sessions/ojo-al-dato-como-evitar-microinfartos-la-gente-de-marketing-beagonpoz' => [],
    '/sessions/how-we-work-gizra-davidbaltha' => [],
    // Short sessions.
    '/sessions/envio-de-correos-transaccionales-sin-caer-spam-en-el-intento-isholgueras' => [],
    '/sessions/integracion-continua-con-circleci-para-drupal-8-regiguren' => [],
    '/sessions/checking-your-sites-health-monitoring-plopesc' => [],
    '/sessions/muerte-al-javascript-larga-vida-al-javascript-sanzante' => [],
    '/sessions/caso-de-uso-configsplit-como-alternativa-multisites-jjcarrion' => [],
    '/sessions/drush-9-creemos-un-comando-en-10-minutos-capynet' => [],
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
