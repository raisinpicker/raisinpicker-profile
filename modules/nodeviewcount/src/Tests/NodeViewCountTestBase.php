<?php

namespace Drupal\nodeviewcount\Tests;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\simpletest\WebTestBase;
use Drupal\user\Entity\User;

/**
 * Defines a base class for testing the nodeviewcount module.
 */
abstract class NodeViewCountTestBase extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['node', 'nodeviewcount'];

  /**
   * User with access to administer nodeviewcount.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * A node with enabled tracking.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $firstTestTrackedNode;

  /**
   * Another one node with enabled tracking.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $secondTestTrackedNode;

  /**
   * A node with disabled tracking.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $testNotTrackedNode;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The Guzzle HTTP client.
   *
   * @var \GuzzleHttp\Client;
   */
  protected $client;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->createRole([
      'access administration pages',
      'administer modules',
      'administer users',
      'access content',
    ], 'administrator', 'administrator');
    $this->adminUser = $this->createUserWithRole('administrator');

    $this->firstTestTrackedNode = $this->drupalCreateNode([
      'type' => 'tracked_page',
      'uid' => $this->adminUser->id(),
      'promoted' => TRUE,
    ]);
    $this->secondTestTrackedNode = $this->drupalCreateNode([
      'type' => 'tracked_page',
      'uid' => $this->adminUser->id(),
      'promoted' => TRUE,
    ]);
    $this->testNotTrackedNode = $this->drupalCreateNode([
      'type' => 'not_tracked_page',
      'uid' => $this->adminUser->id(),
      'promoted' => TRUE,
    ]);
    $this->connection = $this->container->get('database');
    $this->client = \Drupal::service('http_client_factory')
      ->fromOptions(['config/curl' => [CURLOPT_TIMEOUT => 20]]);
    $this->drupalCreateContentType([
        'type' => 'tracked_page',
        'name' => 'Basic page with tracking',
      ]
    );
    // Create one more content type.
    $this->drupalCreateContentType([
      'type' => 'not_tracked_page',
      'name' => 'Basic page with no tracking',
    ]);
    // Create role for authenticated user.
    $this->createRole([
      'access content',
      'change own username',
    ], 'logged', 'logged');
    $this->setNodeviewcountSettings();
  }

  /**
   * Set default nodeviewcount settings for tests.
   */
  private function setNodeviewcountSettings() {
    $node_view_count_settings = $this->config('nodeviewcount.settings');
    $node_view_count_settings
      ->set('user_roles', ['anonymous', 'logged'])
      ->set('excluded_user_roles', ['administrator'])
      ->set('node_types', ['tracked_page'])
      ->set('view_modes', ['full', 'teaser'])
      ->set('logs_life_time', 0)
      ->save();
  }

  /**
   * Send AJAX request to '/nodeviewcount/updateCounter' for updating stats.
   *
   * @param int $nid
   *   Node id to track.
   * @param int $uid
   *   Id of user who viewed the node.
   */
  protected function sendAjaxStatistics($nid, $uid) {
    global $base_url;
    $stats_path = $base_url . '/nodeviewcount/updateCounter';
    $post = ['nid' => $nid, 'uid' => $uid];
    $this->client->post($stats_path, ['form_params' => $post]);
  }

  /**
   * Create user with given role name.
   *
   * @param string $user_role_name
   *   User role name.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   Created user entity.
   */
  protected function createUserWithRole($user_role_name) {
    // Create a user assigned to that role.;
    $edit['name'] = $this->randomMachineName();
    $edit['mail'] = $edit['name'] . '@example.com';
    $edit['pass'] = user_password();
    $edit['status'] = 1;
    $edit['roles'] = [$user_role_name];

    $account = User::create($edit);
    $account->save();

    $this->assertTrue($account->id(), SafeMarkup::format('User created with name %name and pass %pass', [
      '%name' => $edit['name'],
      '%pass' => $edit['pass']
    ]), 'User login');
    if (!$account->id()) {
      return NULL;
    }
    // Add the raw password so that we can log in as this user.
    $account->pass_raw = $edit['pass'];
    return $account;
  }
}
