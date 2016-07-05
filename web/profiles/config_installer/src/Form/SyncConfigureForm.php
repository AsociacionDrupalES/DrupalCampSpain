<?php

namespace Drupal\config_installer\Form;

use Drupal\Core\Archiver\ArchiveTar;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Installation step to configure sync directory or upload a tarball.
 */
class SyncConfigureForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_installer_sync_configure_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#title'] = $this->t('Configure configuration import location');

    $form['sync_directory'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Synchronisation directory'),
      '#default_value' => config_get_config_directory(CONFIG_SYNC_DIRECTORY),
      '#maxlength' => 255,
      '#description' => $this->t('Path to the config directory you wish to import, can be relative to document root or an absolute path.'),
      '#required' => TRUE,
    ];

    $form['import_tarball'] = [
      '#type' => 'file',
      '#title' => $this->t('Select your configuration export file'),
      '#description' => $this->t('If the sync directory is empty you can upload a configuration export file.'),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save and continue'),
      '#weight' => 15,
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $file_upload = $this->getRequest()->files->get('files[import_tarball]', NULL, TRUE);
    $has_upload = FALSE;
    if ($file_upload && $file_upload->isValid()) {
      // The sync directory must be empty if we are doing an upload.
      $form_state->setValue('import_tarball', $file_upload->getRealPath());
      $has_upload = TRUE;
    }
    $sync_directory = $form_state->getValue('sync_directory');
    // If we've customised the sync directory ensure its good to go.
    if ($sync_directory != config_get_config_directory(CONFIG_SYNC_DIRECTORY)) {
      // Ensure it exists and is writeable.
      if (!file_prepare_directory($sync_directory, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS)) {
        $form_state->setErrorByName('sync_directory', t('The directory %directory could not be created or could not be made writable. To proceed with the installation, either create the directory and modify its permissions manually or ensure that the installer has the permissions to create it automatically. For more information, see the <a href="@handbook_url">online handbook</a>.', [
          '%directory' => $sync_directory,
          '@handbook_url' => 'http://drupal.org/server-permissions',
        ]));
      }
    }

    // If no tarball ensure we have files.
    if (!$form_state->hasAnyErrors() && !$has_upload) {
      $sync = new FileStorage($sync_directory);
      if (count($sync->listAll()) === 0) {
        $form_state->setErrorByName('sync_directory', t('No file upload provided and the sync directory is empty'));
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $config_directories, $install_state;
    $sync_directory = $form_state->getValue('sync_directory');
    if ($sync_directory != config_get_config_directory(CONFIG_SYNC_DIRECTORY)) {
      $settings['config_directories'][CONFIG_SYNC_DIRECTORY] = (object) [
        'value' => $sync_directory,
        'required' => TRUE,
      ];
      drupal_rewrite_settings($settings);
      $config_directories[CONFIG_SYNC_DIRECTORY] = $sync_directory;
    }
    if ($path = $form_state->getValue('import_tarball')) {
      // Ensure that we have an empty directory if we're going.
      $sync = new FileStorage($sync_directory);
      $sync->deleteAll();
      try {
        $archiver = new ArchiveTar($path, 'gz');
        $files = [];
        foreach ($archiver->listContent() as $file) {
          $files[] = $file['filename'];
        }
        $archiver->extractList($files, config_get_config_directory(CONFIG_SYNC_DIRECTORY));
        drupal_set_message($this->t('Your configuration files were successfully uploaded, ready for import.'));
      }
      catch (\Exception $e) {
        drupal_set_message($this->t('Could not extract the contents of the tar file. The error message is <em>@message</em>', ['@message' => $e->getMessage()]), 'error');
      }
      drupal_unlink($path);
    }
    // Change the langcode to the site default langcode provided by the
    // configuration.
    $config_storage = new FileStorage(config_get_config_directory(CONFIG_SYNC_DIRECTORY));
    $install_state['parameters']['langcode'] = $config_storage->read('system.site')['langcode'];

  }

}
