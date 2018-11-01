<?php

namespace Drupal\nodeviewcount\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * @ingroup views_field_handlers
 *
 * @ViewsField("node_view_count_id")
 */
class NodeViewCountId extends  FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  public function query () {
    $this->query->addField('nodeviewcount', 'id', 'id');
  }

  /**
   * {@inheritdoc}
   */
  public function render (ResultRow $values) {
    return [
      '#markup' => $values->id,
    ];
  }
}