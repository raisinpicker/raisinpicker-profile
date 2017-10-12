<?php

namespace Drupal\nodeviewcount\Tests;

/**
 * Tests the base functionality of nodeviewcount module.
 *
 * @group nodeviewcount
 */
class NodeViewCountBaseFunctionalityTest extends NodeViewCountTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['views'];

  /**
   * Tests that cron clears old nodeviewcount records.
   */
  public function testExpiredLogs() {
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 0);
    $this->sendAjaxStatistics($this->secondTestTrackedNode->id(), 0);
    sleep(2);
    $this->cronRun();
    $query = $this->connection->select('nodeviewcount', 'nvc')
      ->fields('nvc', ['id']);
    $result = $query->execute()->fetchAll();
    $this->assertEqual(count($result), 2, ' Nodeviewcount statistics is not deleted after cron run.');

    $node_view_count_settings = $this->config('nodeviewcount.settings');
    $node_view_count_settings->set('logs_life_time', 1)->save();
    sleep(2);
    $this->cronRun();
    $result = $query->execute()->fetchAll();
    $this->assertEqual(count($result), 0, ' Nodeviewcount statistics is deleted after cron run.');
  }

  /**
   * Test nodeviewcount js for nodes in full view mode.
   */
  public function testNodesWithFullViewMode() {
    $this->checkFullViewMode('anonymous', $this->firstTestTrackedNode, TRUE);
    $this->checkFullViewMode('anonymous', $this->testNotTrackedNode, FALSE);
    $this->checkFullViewMode('logged', $this->firstTestTrackedNode, TRUE);
    $this->checkFullViewMode('logged', $this->testNotTrackedNode, FALSE);
    $this->checkFullViewMode('administrator', $this->firstTestTrackedNode, FALSE);
    $this->checkFullViewMode('administrator', $this->testNotTrackedNode, FALSE);
  }

  /**
   * Tests nodeviewcount settings for nodes in teaser view mode.
   */
  public function testNodesWithTeaserViewMode() {
    $this->checkTeaserViewMode('anonymous', TRUE);
    $this->checkTeaserViewMode('logged', TRUE);
    $this->checkTeaserViewMode('administrator', FALSE);
    $this->drupalLogin($this->adminUser);
    $edit['view_modes[teaser]'] = FALSE;
    $this->drupalPostForm('admin/config/content/nodeviewcount', $edit, t('Save configuration'));
    $this->drupalGet('admin/config/content/nodeviewcount');
    $this->drupalLogout();
    $this->checkTeaserViewMode('anonymous', FALSE);
    $this->checkTeaserViewMode('logged', FALSE);
    $this->checkTeaserViewMode('administrator', FALSE);
  }

  /**
   * Tests recording of new nodeviewcount record after AJAX call.
   */
  public function testAjaxCall() {
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 0);
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 1);

    $result = $this->connection->select('nodeviewcount', 'nvc')
      ->fields('nvc', ['nid'])
      ->condition('nvc.nid', $this->firstTestTrackedNode->id())
      ->execute()
      ->fetchAll();
    $this->assertEqual(count($result), 2, 'Verifying that the node counter is incremented.');
  }

  /**
   * Check nodeviewcount settings on node view for full view mode.
   *
   * @param string $user_role
   *   User role to access node view page.
   * @param \Drupal\node\NodeInterface $node
   *   Node to access.
   * @param $expected_result
   *   Result that we expect. TRUE if nodeviewcount settings should be included
   *   on the page, FALSE otherwise.
   */
  protected function checkFullViewMode($user_role, \Drupal\node\NodeInterface $node, $expected_result) {
    $user_id = 0;
    // Create user and login if needed.
    if ($user_role !== 'anonymous') {
      $user = $this->createUserWithRole($user_role);
      $user_id = $user->id();
      $this->drupalLogin($user);
    }
    // Go to the node page.
    $this->drupalGet('node/' . $node->id());
    // Check nodeviewcount statistics script.
    if ($expected_result) {
      $this->assertRaw('modules/nodeviewcount/nodeviewcount.js', 'Nodeviewcount statistics library is included.');
      $settings = $this->getDrupalSettings();
      $expectedSettings = [
        $node->id() => [
          'nid' => $node->id(),
          'uid' => $user_id,
          'view_mode' => 'full',
        ],
      ];
      $this->assertEqual($expectedSettings, $settings['nodeviewcount']['data'], 'drupalSettings has right node information.');
    }
    else {
      $this->assertNoRaw('modules/nodeviewcount/nodeviewcount.js', 'Nodeviewcount statistics library is not included.');
    }
    // Logout if needed.
    if ($user_role !== 'anonymous') {
      $this->drupalLogout();
    }
  }

  /**
   * Check nodeviewcount settings on node view for teaser view mode.
   *
   * @param string $user_role
   *   User role for visiting nodes.
   * @param boolean $expected_result
   *   TRUE if nodeviewcount scripts and settings should be included.
   */
  public function checkTeaserViewMode($user_role, $expected_result) {
    $user_id = 0;
    if ($user_role !== 'anonymous') {
      $user = $this->createUserWithRole($user_role);
      $user_id = $user->id();
      $this->drupalLogin($user);
    }
    $this->drupalGet('node');

    if ($expected_result) {
      $this->assertRaw('modules/nodeviewcount/nodeviewcount.js', 'Nodeviewcount statistics library is included.');
      $settings = $this->getDrupalSettings();
      $expectedSettings = [
        $this->firstTestTrackedNode->id() => [
          'nid' => $this->firstTestTrackedNode->id(),
          'uid' => $user_id,
          'view_mode' => 'teaser',
        ],
        $this->secondTestTrackedNode->id() => [
          'nid' => $this->secondTestTrackedNode->id(),
          'uid' => $user_id,
          'view_mode' => 'teaser',
        ]
      ];
      $this->assertEqual($expectedSettings, $settings['nodeviewcount']['data'], 'drupalSettings to mark node as read are present.');
    }
    else {
      $this->assertNoRaw('modules/nodeviewcount/nodeviewcount.js', 'Nodeviewcount statistics library is not included.');
    }

    if ($user_role !== 'anonymous') {
      $this->drupalLogout();
    }
  }

}
