<?php

namespace Drupal\nodeviewcount\Tests;

/**
 * Tests hooks of nodeviewcount module.
 *
 * @group nodeviewcount
 */
class NodeViewCountHooksTest extends NodeViewCountTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['dblog', 'nodeviewcount_hooks_test'];

  /**
   * Tests hook_nodeviewcount_insert().
   */
  public function testNodeviewcountInsertHook() {
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 0);
    $message = $this->connection->select('watchdog', 'wd')
      ->fields('wd', ['message'])
      ->condition('type', 'nodeviewcount')
      ->execute()
      ->fetchField();
    $this->assertEqual($message, $this->firstTestTrackedNode->getTitle(), 'Nodeviewcount hook is called.');
  }

}
