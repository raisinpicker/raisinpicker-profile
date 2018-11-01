<?php

/**
 * Page callback: Add node view ajax.
 *
 * @param object $node
 *   The node for counting.
 * @param object $user
 *   The user for counting.
 */
function nodeviewcount_count_node_view_ajax($node, $user) {
  if ($node && $user) {
    nodeviewcount_insert_node_view($node->nid, $user->uid);
  }
}
