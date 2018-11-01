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
function nodeviewcount_insert_node_view($nid, $uid) {
  $node_view = nodeviewcount_db_insert_node_view($nid, $uid);
  return $node_view;
}

/**
 * Check node on content type for node view count.
 *
 * @param object $node
 *   The node for checking.
 *
 * @return bool
 *   TRUE if the node content type checked for count views, otherwise FALSE.
 */
function nodeviewcount_is_node_type_count($node) {
  $node_types = variable_get('nodeviewcount_node_types', array());
  if (!empty($node_types[$node->type])) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Check node on content type for node view count.
 *
 * @param string $view_mode
 *   The node view mode for checking.
 *
 * @return bool
 *   TRUE if the node content type checked for count views, otherwise FALSE.
 */
function nodeviewcount_is_node_view_mode_count($view_mode) {
  $node_view_modes = variable_get('nodeviewcount_node_view_modes', array());
  if (!empty($node_view_modes[$view_mode])) {
    return TRUE;
  }
  return FALSE;
}

function nodeviewcount_get_node_view_modes_count() {
  $node_view_modes_count = array();
  $node_view_modes = variable_get('nodeviewcount_node_view_modes', array());
  foreach ($node_view_modes as $node_view_mode) {
    if ($node_view_mode !== 0) {
      $node_view_modes_count[] = $node_view_mode;
    }
  }
  return $node_view_modes_count;
}

/**
 * Check user on user roles for node view count.
 *
 * @param object $user
 *   The user for checking.
 *
 * @return bool
 *   TRUE if the user role checked for count views, otherwise FALSE.
 */
function nodeviewcount_is_user_role_count($user) {
  $nodeviewcount_user_roles = variable_get('nodeviewcount_user_roles', array());
  $excluded_user_roles = variable_get('nodeviewcount_excluded_user_roles', array());
  $user_roles_ids = array_keys($user->roles);
  foreach ($user_roles_ids as $role_id) {
    if (!empty($excluded_user_roles[$role_id])) {
      return FALSE;
    }
  }
  foreach ($user_roles_ids as $role_id) {
    if (!empty($nodeviewcount_user_roles[$role_id])) {
      return TRUE;
    }
  }
  return FALSE;
}

/**
 * Count node views.
 *
 * @param object $node
 *   Chosen node for counting.
 * @param object|NULL $user
 *   Chosen user for counting.
 * @param bool $distinct_users
 *   Distinct user or not.
 *
 * @return int
 *   Count of node views.
 */
function nodeviewcountcount_count_node_views($node, $user = NULL, $distinct_users = FALSE) {
  $is_node_count = nodeviewcount_is_node_type_count($node);
  if (!$is_node_count) {
    return FALSE;
  }
  $is_user_count = nodeviewcount_is_user_role_count($user);
  if (!is_null($user) && !$is_user_count) {
    return FALSE;
  }
  $node_view_count = nodeviewcount_db_count_node_views($node, $user, $distinct_users);
  return $node_view_count;
}
