<?php

namespace Drupal\nodeviewcount;

use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Drupal\user\UserInterface;

/**
 * Provides an interface for interfering with nodeviewcount records.
 */
interface NodeViewCountRecordsManagerInterface {

  /**
   * Insert nodeviewcount record to the database.
   *
   * @param int $uid
   *   Id of user which viewed the content.
   * @param int $nid
   *   Id of viewed node.
   */
  public function insertRecord($uid, $nid);

  /**
   * Return count of node views.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node object.
   * @param \Drupal\user\UserInterface $user
   *   Chosen user for counting or NULL for counting node views of all users.
   * @param bool $distinct_users
   *   Should same user node view count as 1 view.
   *
   * @return int
   *   Count of node views.
   */
  public function getNodeViewsCount(NodeInterface $node, UserInterface $user = NULL, $distinct_users = FALSE);

  /**
   * Delete old nodeviewcount records from database.
   *
   * @param int $time
   *   Lifetime of the records in milliseconds.
   */
  public function deleteOldRecords($time);

  /**
   * Checks whether node in given view mode should be counted in statistics.
   *
   * @param string $view_mode
   *   View mode of node.
   *
   * @return bool
   *   TRUE if node in given view mode should be counted in statistics.
   */
  public function isRecordableForViewMode($view_mode);

  /**
   * Checks whether node should be counted in statistics for given user role.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   User account object to fetch roles from.
   *
   * @return bool
   *   TRUE if node should be counted in statistics for given user role.
   */
  public function isRecordableForUserRole(AccountInterface $account);

  /**
   * Checks whether node of given node type should be counted in statistics.
   *
   * @param NodeInterface $node
   *   Node object.
   *
   * @return bool
   *   TRUE if node of given node type should be counted in statistics.
   */
  public function isRecordableForNodeType(NodeInterface $node);

  /**
   * Get lifetime of the nodeviewcount records.
   *
   * @return int
   *   Lifetime of the nodeviewcount records.
   */
  public function getLogsLifeTime();

}
