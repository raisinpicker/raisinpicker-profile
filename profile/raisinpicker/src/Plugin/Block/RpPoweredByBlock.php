<?php

namespace Drupal\raisinpicker\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Powered by raisinpicker' block.
 *
 * @Block(
 *   id = "raisinpicker_powered_by_block",
 *   admin_label = @Translation("Powered by raisinpicker")
 * )
 */
class RaisinpickerPoweredByBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return ['#markup' => '<span>' . $this->t('Powered by <a href=":poweredby">Raisin Picker</a>', [':poweredby' => 'http://raisinpicker.github.io']) . '</span>'];
  }

}
