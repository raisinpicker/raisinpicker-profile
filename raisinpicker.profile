<?php

/**
 * @file
 * Enables modules and site configuration for a raisinpicker site installation.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 * Allows the profile to alter the site configuration form.
 */
function raisinpicker_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
	$form['site_information']['site_name']['#placeholder'] = t('Raisin Picker');
}
/** 
* Implements hook_form_FORM_ID_alter().
*/
function raisinpicker_form_node_relation_form_alter(&$form, FormStateInterface &$form_state) {

  foreach (array_keys($form['actions']) as $action) {
    if ($action != 'preview' && isset($form['actions'][$action]['#type']) && $form['actions'][$action]['#type'] === 'submit') {
      $form['actions'][$action]['#submit'][] = '_raisinpicker_form_relation_node_form';
    }
  }
}

function _raisinpicker_form_relation_node_form($form, FormStateInterface $form_state) {

  $form_state->setRedirect('view.content_list.page_1');
 //    $response = new AjaxResponse();
 //    if ($form_state->hasAnyErrors()) {
 //      $response->addCommand(new ReplaceCommand('#modal_example_form', $form));
 //    }
 //    else {
 //      $response->addCommand(new OpenModalDialogCommand("Success!", 'The modal form has been submitted.', ['width' => 800]));
 //    }
 //    return $response;  
 }
 
 
/**
 * Implements hook_field_widget_info() 
 */
function raisinpicker_field_widget_info() {
  return array(
    'nouislider_widget' => array(
      'label' => t('noUiSlider field'),
      'field types' => array('integer'),
    ),
    'rp_chosen_widget' => array(
      'label' => t('RP Chosen'),
      'field types' => array('entity_reference'),
    ),
  );
}
  

/**
 * Implements hook_field_widget_form_alter().
 */
function raisinpicker_field_widget_daterange_default_form_alter(&$element, &$form_state, $context) {
  $element['#theme_wrappers'][0] = 'container';
}

function raisinpicker_field_widget_daterange_datelist_form_alter(&$element, &$form_state, $context) {
  $element['#theme_wrappers'][0] = 'container';
}





/**
 * Implements hook_library_info_alter().
 */
// function raisinpicker_library_info_alter(&$libraries, $module) {
// 	if ($module == 'raisinpicker') {
// 		/** @var \Drupal\raisinpicker\ThrobberManagerInterface $throbber_manager */
// 		$throbber_manager = Drupal::service('raisinpicker.throbber_manager');

// 		if (isset($libraries['raisinpicker.throbber'])) {
// 	//    Add css for chosen throbber.
// 		$throbber = $throbber_manager->loadThrobberInstance('throbber_three_bounce');
// 		$libraries['raisinpicker.throbber']['css']['theme'][$throbber->getCssFile()] = array();
// 		}
// 	}
// }

/**
 * Implements hook_page_attachments().
 */
//  function raisinpicker_page_attachments(array &$page) {
//    /** @var \Drupal\raisinpicker\ThrobberManagerInterface $throbber_manager */
//    $throbber_manager = Drupal::service('raisinpicker.throbber_manager');
//    $throbber = 'throbber_three_bounce';
//    if ($throbber_manager->getDefinition($throbber, FALSE) && $throbber_manager->RouteIsApplicable()) {
//      /** @var \Drupal\raisinpicker\ThrobberPluginInterface $throbber */
//      $throbber = $throbber_manager->loadThrobberInstance('throbber_three_bounce');
//      $settings = array(
//        'markup' => '<div class="ajax-throbber sk-three-bounce">
//               <div class="sk-child sk-bounce1"></div>
//               <div class="sk-child sk-bounce2"></div>
//               <div class="sk-child sk-bounce3"></div>
//             </div>',
//        'hideAjaxMessage' => FALSE,
//        'alwaysFullscreen' => TRUE,
//        'throbberPosition' => 'body',
//      );

//      $page['#attached']['drupalSettings']['ajaxLoader'] = $settings;
//      $page['#attached']['library'][] = 'raisinpicker/raisinpicker.throbber';
//    }
//  }

/**
 * Implements hook_theme().
 */
function raisinpicker_theme() {
  return [
    'term_tree' => [
      'render element' => 'element',
      'function' => 'raisinpicker_term_tree',
    ],
    'cheatsheet' => [
    ],
  ];
}

/**
 * Themes the term tree display (as opposed to the select widget).
 */
function raisinpicker_term_tree($variables) {
  $element = &$variables['element'];
  $data = &$element['#data'];

  $tree = [];

  // For each selected term:
  foreach ($data as $item) {
    // Loop if the term ID is not zero:
    $values = [];
    $tid = $item['target_id'];
    $original_tid = $tid;
    while ($tid != 0) {
      // Unshift the term onto an array
      array_unshift($values, $tid);

      // Repeat with parent term
      $tid = _raisinpicker_get_parent($tid);
    }

    $current = &$tree;
    // For each term in the above array:
    foreach ($values as $tid) {
      // current[children][term_id] = new array
      if (!isset($current['children'][$tid])) {
        $current['children'][$tid] = ['selected' => FALSE];
      }

      // If this is the last value in the array, tree[children][term_id][selected] = true
      if ($tid == $original_tid) {
        $current['children'][$tid]['selected'] = TRUE;
      }

      $current['children'][$tid]['tid'] = $tid;
      $current = &$current['children'][$tid];
    }
  }
  $output = "<div class='term-tree-list'>";
  $output .= _raisinpicker_output_list_level($element, $tree);
  $output .= "</div>";
  return $output;
}

/**
 * Helper function to get the parent of tid.
 */
function _raisinpicker_get_parent($tid) {
	$query = "SELECT p.parent_target_id FROM {taxonomy_term__parent} p WHERE p.entity_id = :tid";
	$from = 0;
	$count = 1;
	$args = [':tid' => $tid];
	$database = \Drupal::database();
	$result = $database->queryRange($query, $from, $count, $args);
	$parent_tid = 0;
	foreach ($result as $term) {
		$parent_tid = $term->parent_target_id;
	}
	return $parent_tid; 
}

/**
 * Helper function to output a single level of the term reference tree
 * display.
 */
function _raisinpicker_output_list_level(&$element, &$tree) {
  $output = '';
  if (isset($tree['children']) && is_array($tree['children']) && count($tree['children']) > 0) {
    $output = '<ul class="term">';
    foreach ($tree['children'] as &$item) {
      $term = \Drupal\taxonomy\Entity\Term::load($item['tid']);
      $uri['options']['html'] = TRUE;
      $url = $term->toUrl();
      $link_options = array(
        'attributes' => array(
          'class' => array(
            'badge', 'badge-light', 'badge-pill',
          ),
        ),
      );
      $url->setOptions($link_options);
      $class = $item['selected'] ? 'selected' : 'unselected';
      $output .= "<li class='$class'>";
      $output .= \Drupal::service('link_generator')->generate($term->label(), $url);
      if (isset($item['children'])) {
        $output .= _raisinpicker_output_list_level($element, $item);
      }
      $output .= "</li>";
    }
    $output .= '</ul>';
  }
  return $output;
}
