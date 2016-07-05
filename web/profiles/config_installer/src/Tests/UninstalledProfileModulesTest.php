<?php

namespace Drupal\config_installer\Tests;

/**
 * Tests the config installer where modules installed by a profile have been uninstalled.
 *
 * @group ConfigInstaller
 */
class UninstalledProfileModulesTest extends ConfigInstallerTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUpSyncForm() {
    // Upload the tarball.
    $this->drupalPostForm(NULL, ['files[import_tarball]' => $this->getTarball()], 'Save and continue');
  }

  /**
   * {@inheritdoc}
   */
  protected function getTarball() {
    // Exported configuration after a minimal profile install.
    return __DIR__ . '/Fixtures/standard-without-config-8.2.x.tar.gz';
  }

  /**
   * Runs tests after install.
   */
  public function testInstaller() {
    //$this->assertUrl('<front>');
    $this->assertResponse(200);
    // Ensure that all modules, profile and themes have been installed and have
    // expected weights.
    $sync = \Drupal::service('config.storage.sync');
    $sync_core_extension = $sync->read('core.extension');
    $this->assertIdentical($sync_core_extension, \Drupal::config('core.extension')->get());
    $this->assertFalse(\Drupal::moduleHandler()->moduleExists('contact'), 'Contact module is not installed.');
  }

}
