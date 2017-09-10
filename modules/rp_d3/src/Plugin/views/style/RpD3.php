<?php

namespace Drupal\rp_d3\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render each item in an ordered or unordered list.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "rp_d3",
 *   title = @Translation("D3 Chart"),
 *   help = @Translation("D3 Visualization"),
 *   theme = "rp_d3",
 *   display_types = {"normal"}
 * )
 */
class RpD3 extends StylePluginBase {

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = FALSE;

  /**
   * Set default options
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['chart_title'] = array('default' => 'D3 Chart');
    $options['chart_axis_mapping'] = array('default' => '');   
    $options['chart_width'] = array('default' => '400');
    $options['chart_height'] = array('default' => '300');
    return $options;
  }

  /**
   * Render the given style.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['chart_title'] = array(
      '#title' => t('Chart Title'),
      '#type' => 'textfield',
      '#size' => '60',
      '#default_value' => $this->options['chart_title'],
    );
    $form['chart_axis_mapping'] = array(
      '#title' => t('Chart Axis Mapping'),
      '#type' => 'textfield',
      '#description' => t('Each axis need to be placed as comma(,) separtor.'),
      '#size' => '60',
      '#default_value' => $this->options['chart_axis_mapping'],
    );
    $form['chart_width'] = array(
      '#title' => t('Chart Width'),
      '#type' => 'textfield',
      '#size' => '60',
      '#default_value' => $this->options['chart_width'],
    );
    $form['chart_height'] = array(
      '#title' => t('Chart Height'),
      '#type' => 'textfield',
      '#size' => '60',
      '#default_value' => $this->options['chart_height'],
    );
  }
  
  /**
   * @see template_preprocess_rp_d3()
   * @return array|null
   */
  public function get_render_fields() {
    return $this->rendered_fields;
  }
}
