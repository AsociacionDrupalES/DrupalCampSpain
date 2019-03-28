<?php

namespace Drush\Commands;

use Drush\Commands\DrushCommands;
use Drush\SiteAlias\SiteAliasManagerAwareInterface;
use Drush\SiteAlias\SiteAliasManagerAwareTrait;

/**
 * Sync tool.
 */
class SyncToolsCommands extends DrushCommands implements SiteAliasManagerAwareInterface {

  use SiteAliasManagerAwareTrait;

  /**
   * @hook interact SyncTools:sync-files-from
   */
  public function interactSyncFilesFrom($input, $output) {
    $this->_interact($input, $output);
  }

  /**
   * @hook interact SyncTools:sync-db-from
   */
  public function interactSyncDbFrom($input, $output) {
    $this->_interact($input, $output);
  }

  /**
   * @hook interact SyncTools:sync-all-from
   */
  public function interactSyncAllFrom($input, $output) {
    $this->_interact($input, $output);
  }


  /**
   * Helper. Interact hook functionality.
   * @param $input
   * @param $output
   */
  private function _interact($input, $output) {
    $choices = [];
    $alias = $input->getArgument('alias');

    if (empty($alias)) {
      $alias_list = $this->siteAliasManager()->getMultiple(NULL);

      foreach ($alias_list as $alias_item) {
        $alias_name = $alias_item->name();
        $is_local = $alias_item->isLocal();

        if (!$is_local) {
          $choices[] = $alias_name;
        }
      }

      $alias = $this->io()->choice(dt("Choose the alias locations of the files"), $choices, 'prod');
      $input->setArgument('alias', $choices[$alias]);
    }
  }

  /**
   * Pull files from a alias site.
   *
   * @param $alias string Drush alias.
   * @command SyncTools:sync-files-from
   * @aliases sff
   * @bootstrap none
   */
  public function syncFilesFrom($alias) {
    drush_invoke_process('@none', 'rsync', [$alias . ':sites/default/files', '@self:sites/default/']);
    $this->io()->success('Files synced from ' . $alias);
  }


  /**
   * Pull DB from a alias site.
   *
   * @param $alias string Drush alias name.
   * @command SyncTools:sync-db-from
   * @aliases sdbf
   */
  public function syncDbFrom($alias) {
    $this->logger()->notice('Dropping old database.');
    drush_invoke_process('@none', 'sql-drop');

    $this->logger()->notice('Importing new database.');
    drush_invoke_process('@none', 'sql:sync', [$alias, '@self']);

    $this->io()->success('DB synced from ' . $alias);
  }


  /**
   * Pull Files and DB from a alias site.
   *
   * @param $alias string Drush alias name.
   * @command SyncTools:sync-all-from
   * @aliases saf
   */
  public function syncAllFrom($alias) {
    drush_invoke_process('@none', 'sff', [$alias]);
    drush_invoke_process('@none', 'sdbf', [$alias]);

    $this->io()->table(
      ['Acción', 'Estado'],
      [['Sincronizar files desde ' . $alias, 'Hecho'], ['Sincronizar DB desde ' . $alias, 'Hecho']]
    );

  }

}
