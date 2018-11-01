<?php

namespace Drupal\nodeviewcount\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * @ingroup views_field_handlers
 *
 * @ViewsField("node_view_count_node_id")
 */
class NodeViewCountNodeId extends FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  public function render (ResultRow $values) {
    return [
      '#markup' => $values->_entity->id(),
    ];
  }
}