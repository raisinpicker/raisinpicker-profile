<?php

namespace Drupal\views_simplechart\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render each item in an ordered or unordered list.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "views_simplechart",
 *   title = @Translation("Views Simple Chart"),
 *   help = @Translation("Simple Chart Visualization"),
 *   theme = "views_simplechart_graph",
 *   display_types = {"normal"}
 * )
 */
class ViewsSimplechart extends StylePluginBase {

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
    $options['chart_title'] = array('default' => 'Simple Chart');
    $options['chart_axis_mapping'] = array('default' => '');
    $options['chart_type_stacked'] = array('default' => 'no');
    $options['chart_type'] = array('default' => 'bar');
    $options['chart_legend_position'] = array('default' => 'bottom');
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
    $form['chart_type'] = array(
      '#type' => 'radios',
      '#title' => t('Chart type'),
      '#options' => array(
        'bar' => t('Bar'),
        'column' => t('Column'),
        'line' => t('Line'),
        'calendar' => t('Calendar')),
      '#default_value' => $this->options['chart_type'],
    );
    $form['chart_type_stacked'] = array(
      '#type' => 'radios',
      '#title' => t('Do you want Stack in Graph?'),
      '#options' => array('yes' => t('Yes'),'no' => t('No')),
      '#description' => t('This is applicable only for Bar and Column chart.'),
      '#default_value' => $this->options['chart_type_stacked'],
    );
    $form['chart_legend_position'] = array(
      '#type' => 'radios',
      '#title' => t('Chart Legend Position'),
      '#options' => array('left' => t('Left'),'right' => t('Right'),'top' => t('Top'),'bottom' => t('Bottom'),'none' => t('None')),
      '#default_value' => $this->options['chart_legend_position'],
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
   * @see template_preprocess_views_simplechart()
   * @return array|null
   */
  public function get_render_fields() {
    return $this->rendered_fields;
  }
}
