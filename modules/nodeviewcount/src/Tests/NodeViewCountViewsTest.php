<?php

namespace Drupal\nodeviewcount\Tests;

/**
 * Tests views of nodeviewcount module.
 *
 * @group nodeviewcount
 */
class NodeViewCountViewsTest extends NodeViewCountTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('views', 'block');

  /**
   * Tests Node views count statistics view.
   */
  public function testNodeViewsCountStatisticsView() {
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 0);
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 1);
    $this->sendAjaxStatistics($this->secondTestTrackedNode->id(), 0);
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('admin/config/content/node-views-count-statistics');
    $this->assertStatisticsViewRowExists($this->firstTestTrackedNode->getTitle(), 2);
    $this->assertStatisticsViewRowExists($this->secondTestTrackedNode->getTitle(), 1);
    $this->assertStatisticsViewRowExists($this->testNotTrackedNode->getTitle(), 0);
    $this->drupalLogout();
  }

  /**
   * Tests Top viewed nodes view.
   */
  public function testTopViewedNodes() {
    $this->drupalPlaceBlock('views_block:top_viewed_nodes-block');
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 0);
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), 1);
    $this->sendAjaxStatistics($this->secondTestTrackedNode->id(), 0);
    $this->drupalGet('<front>');
    $this->assertFieldByXpath("(//div[@class='views-field views-field-title']/span/a)[1]", $this->firstTestTrackedNode->getTitle());
    $this->assertFieldByXpath("(//div[@class='views-field views-field-title']/span/a)[2]", $this->secondTestTrackedNode->getTitle());
  }

  /**
   * Tests Last views per user view.
   */
  public function testLastViewsPerUserView() {
    $this->drupalPlaceBlock('views_block:last_views_per_user-block');
    $user = $this->createUserWithRole('logged');
    $this->drupalLogin($user);
    $this->sendAjaxStatistics($this->secondTestTrackedNode->id(), 0);
    $this->sendAjaxStatistics($this->firstTestTrackedNode->id(), $user->id());
    sleep(5);
    $this->sendAjaxStatistics($this->secondTestTrackedNode->id(), $user->id());
    $this->drupalGet('<front>');
    $this->assertFieldByXpath("(//div[@class='views-field views-field-title']/span/a)[1]", $this->secondTestTrackedNode->getTitle());
    $this->assertFieldByXpath("(//div[@class='views-field views-field-title']/span/a)[2]", $this->firstTestTrackedNode->getTitle());
    $this->drupalLogout();
  }

  /**
   * Assert that views row with given title and count columns values exists.
   *
   * @param string $title
   *   Title of the node.
   * @param int $count
   *   Count of views.
   */
  private function assertStatisticsViewRowExists($title, $count) {
    $value = $this->xpath("//td[normalize-space(../td[@class='views-field views-field-title']/text()) = '{$title}'][2]");
    $value = (string) reset($value);
    $this->assertEqual($value, $count);
  }

}
