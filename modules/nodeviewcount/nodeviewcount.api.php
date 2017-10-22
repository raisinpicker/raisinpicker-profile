<?php

/**
 * @file
 * Documentation for nodeviewcount module API.
 */

use Drupal\node\NodeInterface;

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Acts when new nodeviewcount record tries to be inserted into database.
 *
 * This hook can return FALSE to prevent insertion of nodeviewcount record.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node object to be recorded in nodeviewcount statistics.
 * @param string $view_mode
 *   View mode of the node.
 *
 * @return bool
 *   FALSE to prevent insertion of new nodeviewcount record.
 */
function hook_nodeviewcount_insert(NodeInterface $node, $view_mode) {
  // Record statistics only for nodes in default language.
  if (!$node->language()->isDefault()) {
    return FALSE;
  }
  return TRUE;
}

/**
 * @} End of "addtogroup hooks".
 */
