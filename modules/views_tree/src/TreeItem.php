<?php

/**
 * @file
 * Contains \Drupal\views_tree\TreeItem.
 */

namespace Drupal\views_tree;

use Traversable;

class TreeItem implements \IteratorAggregate {

  protected $node;

  /**
   * @var \Drupal\views_tree\TreeItem
   */
  protected $leaves = [];

  /**
   * Creates a new TreeItem instance.
   *
   * @param $node
   * @param array $leaves
   */
  public function __construct($node, array $leaves = []) {
    $this->setNode($node);
    $this->setLeaves($leaves);
  }

  /**
   * @return mixed
   */
  public function getNode() {
    return $this->node;
  }

  /**
   * @param mixed $node
   */
  public function setNode($node) {
    $this->node = $node;
  }

  /**
   * @return array
   */
  public function getLeaves() {
    return $this->leaves;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    return new \ArrayIterator($this->leaves);
  }

  /**
   * @param array $leaves
   *
   * @return $this
   */
  public function setLeaves(array $leaves) {
    foreach ($leaves as &$leave) {
      if (!$leave instanceof static) {
        $leave = new TreeItem($leave);
      }
    }
    $this->leaves = $leaves;
    return $this;
  }

  /**
   * @param $item
   *
   * @return $this
   */
  public function addLeave($item) {
    if (!$item instanceof static) {
      $item = new TreeItem($item);
    }
    $this->leaves[] = $item;
    return $this;
  }

}
