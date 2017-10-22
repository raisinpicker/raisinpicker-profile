<?php

/**
 * @file
 * Contains \Drupal\Tests\views_tree\Unit\TreeHelperTest.
 */

namespace Drupal\Tests\views_tree\Unit;

use Drupal\views\ResultRow;
use Drupal\views_tree\Plugin\views\style\Tree;
use Drupal\views_tree\TreeItem;
use Drupal\views_tree\TreeHelper;

/**
 * @coversDefaultClass \Drupal\views_tree\TreeHelper
 * @group views_tree
 */
class TreeHelperTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers ::getTreeFromResult
   */
  public function testGetTreeFromResultFromEmptyResult() {
    $tree_helper = new TreeHelper();
    $this->assertEquals(new TreeItem(NULL, []), $tree_helper->getTreeFromResult([]));
  }

  /**
   * @covers ::getTreeFromResult
   */
  public function testGetTreeFromResultWithNoHierarchy() {
    $tree_helper = new TreeHelper();
    $tree_data = [];
    $tree_data[] = new ResultRow([
      'views_tree_main' => 1,
      'views_tree_parent' => 0,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 2,
      'views_tree_parent' => 0,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 3,
      'views_tree_parent' => 0,
    ]);

    $expected_tree = new TreeItem(NULL, $tree_data);

    $this->assertEquals($expected_tree, $tree_helper->getTreeFromResult($tree_data));
  }

  /**
   * @covers ::getTreeFromResult
   */
  public function testGetTreeFromResultWithOneLevelHierarchy() {
    $tree_helper = new TreeHelper();
    $tree_data = [];
    $tree_data[] = new ResultRow([
      'views_tree_main' => 1,
      'views_tree_parent' => 0,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 2,
      'views_tree_parent' => 1,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 3,
      'views_tree_parent' => 1,
    ]);

    $expected_tree = (new TreeItem(NULL))->addLeave(
      (new TreeItem($tree_data[0]))
        ->addLeave($tree_data[1])
        ->addLeave($tree_data[2])
    );

    $this->assertEquals($expected_tree, $tree_helper->getTreeFromResult($tree_data));
  }

  /**
   * @covers ::getTreeFromResult
   */
  public function testGetTreeFromResultWithMultipleLevelHierarchy() {
    $tree_helper = new TreeHelper();
    $tree_data = [];
    $tree_data[] = new ResultRow([
      'views_tree_main' => 1,
      'views_tree_parent' => 0,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 2,
      'views_tree_parent' => 1,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 3,
      'views_tree_parent' => 1,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 4,
      'views_tree_parent' => 1,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 5,
      'views_tree_parent' => 4,
    ]);
    $tree_data[] = new ResultRow([
      'views_tree_main' => 6,
      'views_tree_parent' => 5,
    ]);

    $expected_tree = (new TreeItem(NULL))->addLeave(
      (new TreeItem($tree_data[0]))
        ->addLeave($tree_data[1])
        ->addLeave($tree_data[2])
        ->addLeave((new TreeItem($tree_data[3]))
          ->addLeave((new TreeItem($tree_data[4]))
            ->addLeave(new TreeItem($tree_data[5]))
          ))
    );
    $this->assertEquals($expected_tree, $tree_helper->getTreeFromResult($tree_data));
  }

  /**
   * @covers ::applyFunctionToTree
   */
  public function testApplyFunctionToTree() {
    $tree = new TreeItem(1);
    $tree->addLeave((new TreeItem(2))
      ->addLeave(2.5)
      ->addLeave(3.5)
    );

    $expected_tree = new TreeItem(2);
    $expected_tree->addLeave((new TreeItem(3))
      ->addLeave(3.5)
      ->addLeave(4.5)
    );

    $tree_helper = new TreeHelper();
    $result = $tree_helper->applyFunctionToTree($tree, function ($i) { return $i + 1; });
    $this->assertEquals($expected_tree, $result);
  }

  /**
   * @covers ::applyFunctionToTree
   */
  public function testApplyFunctionToTreeWithNulls() {
    $tree = new TreeItem(NULL);
    $tree->addLeave((new TreeItem(2))
      ->addLeave(NULL)
      ->addLeave(3.5)
    );

    $expected_tree = new TreeItem(NULL);
    $expected_tree->addLeave((new TreeItem(3))
      ->addLeave(NULL)
      ->addLeave(4.5)
    );

    $tree_helper = new TreeHelper();
    $result = $tree_helper->applyFunctionToTree($tree, function ($i) { return $i + 1; });
    $this->assertEquals($expected_tree, $result);
  }

}
