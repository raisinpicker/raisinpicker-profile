<?php

/**
 * @file
 * Contains \Drupal\views_tree\Plugin\views\style\TreeHelper.
 */

namespace Drupal\views_tree\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\HtmlList;

/**
 * Style plugin to render each item as hierarchy.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "tree",
 *   title = @Translation("TreeHelper (Adjacency model)"),
 *   help = @Translation("Display the results as a nested tree"),
 *   theme = "views_tree",
 *   display_types = {"normal"}
 * )
 */
class Tree extends HtmlList {

  /**
   * {@inheritdoc}
   */
  protected $usesRowPlugin = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $usesFields = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $usesGrouping = FALSE;

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['class'] = ['default' => ''];
    $options['wrapper_class'] = ['default' => 'item-list'];
    $options['main_field'] = ['default' => ''];
    $options['parent_field'] = ['default' => ''];
    $options['collapsible_tree'] = ['default' => 0];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $fields = ['' => t('<None>')];

    foreach ($this->displayHandler->getHandlers('field') as $field => $handler) {
      $fields[$field] = $handler->adminLabel();
    }

    $events = ['click' => t('On Click'), 'mouseover' => t('On Mouseover')];

    $form['type']['#description'] = t('Whether to use an ordered or unordered list for the retrieved items. Most use cases will prefer Unordered.');

    // Unused by the views tree module at this time.
    unset($form['wrapper_class']);
    unset($form['class']);

    $form['main_field'] = [
      '#type' => 'select',
      '#title' => t('Main field'),
      '#options' => $fields,
      '#default_value' => $this->options['main_field'],
      '#description' => t('Select the field with the unique identifier for each record.'),
      '#required' => TRUE,
    ];

    $form['parent_field'] = [
      '#type' => 'select',
      '#title' => t('Parent field'),
      '#options' => $fields,
      '#default_value' => $this->options['parent_field'],
      '#description' => t('Select the field that contains the unique identifier of the record\'s parent.'),
    ];

    $form['collapsible_tree'] = [
      '#type' => 'radios',
      '#title' => t('Collapsible view'),
      '#default_value' => $this->options['collapsible_tree'],
      '#options' => [
        0 => t('Off'),
        'expanded' => t('Expanded'),
        'collapsed' => t('Collapsed'),
      ],
    ];
  }

}
