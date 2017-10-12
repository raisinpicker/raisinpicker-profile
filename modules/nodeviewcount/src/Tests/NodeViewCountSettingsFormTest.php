<?php

namespace Drupal\nodeviewcount\Tests;

/**
 * Tests settings form of nodeviewcount module.
 *
 * @group nodeviewcount
 */
class NodeViewCountSettingsFormTest extends NodeViewCountTestBase {

  /**
   * Tests that default settings displayed right on the form.
   */
  public function testDefaultSettings() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('admin/config/content/nodeviewcount');

    $this->assertFieldChecked('edit-node-types-tracked-page');
    $this->assertNoFieldChecked('edit-node-types-not-tracked-page');
    $this->assertFieldChecked('edit-view-modes-full');
    $this->assertFieldChecked('edit-view-modes-teaser');
    $this->assertFieldChecked('edit-user-roles-anonymous');
    $this->assertFieldChecked('edit-user-roles-logged');
    $this->assertNoFieldChecked('edit-user-roles-authenticated');
    $this->assertNoFieldById('edit-user-roles-administrator');
    $this->assertNoFieldById('edit-excluded-user-roles-anonymous');
    $this->assertNoFieldById('edit-excluded-user-roles-logged');
    $this->assertNoFieldChecked('edit-excluded-user-roles-authenticated');
    $this->assertFieldChecked('edit-excluded-user-roles-administrator');
    $this->assertOptionSelected('edit-logs-life-time', 0);

    $this->drupalLogout();
  }

  /**
   * Tests that settings are changed on form submit.
   */
  public function testEditingSettings() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('admin/config/content/nodeviewcount');

    $edit = [
      'logs_life_time' => 604800,
      'user_roles[authenticated]' => TRUE,
    ];
    $this->drupalPostForm(NULL, $edit, t('Save configuration'));
    $this->assertFieldChecked('edit-user-roles-authenticated');
    $this->assertNoFieldById('edit-excluded-user-roles-authenticated');
    $this->assertOptionSelected('edit-logs-life-time', 604800);

    $this->drupalLogout();
  }

}
