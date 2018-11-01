<?php

/**
 * Insert node view.
 *
 * @param int $nid
 *   The viewed node.
 * @param int $uid
 *   The user who viewed node.
 *
 * @return bool|int
 *   Count written records or FALSE.
 */
function nodeviewcount_db_insert_node_view($nid, $uid) {
  $fields = array(
    'nid' => $nid,
    'uid' => $uid,
    'timestamp' => REQUEST_TIME,
  );
  return drupal_write_record('nodeviewcount', $fields);
}

/**
 * Count node views.
 *
 * @param object $node
 *   Node.
 * @param object $user
 *   User.
 * @param bool $distinct_users
 *   Distinct user or not.
 *
 * @return int
 *   Count of node views.
 */
function nodeviewcount_db_count_node_views($node, $user = NULL, $distinct_users = FALSE) {
  $query = db_select('nodeviewcount', 'nvc');
  $query->condition('nid', $node->nid, '=');
  if ($user) {
    $query->condition('uid', $user->uid, '=');
  }
  if ($distinct_users) {
    $query->addExpression('COUNT(DISTINCT uid)');
  }
  else {
    $query->addExpression('COUNT(*)');
  }
  $result = $query->execute()->fetchAll();
  return $result;
}
