<?php

/**
 * Nodeviewcount admin form.
 */
function nodeviewcount_admin_settings_form($form, &$form_state) {
  // Get node types options.
  $options_node_type = nodeviewcount_get_checked_node_types();
  $form['nodeviewcount_node_types'] = array(
    '#title' => t('Node types'),
    '#description' => t('Choose content types to count views of node.'),
    '#type' => 'checkboxes',
    '#options' => $options_node_type,
    '#default_value' => variable_get('nodeviewcount_node_types', array()),
  );
  // Get node view modes.
  $form['nodeviewcount_node_modes'] = array(
    '#title' => t('Node view modes'),
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $options_node_view_modes = nodeviewcount_get_node_view_modes();
  $nodeviewcount_node_view_modes = variable_get('nodeviewcount_node_view_modes', array());
  $form['nodeviewcount_node_modes']['nodeviewcount_node_view_modes'] = array(
    '#description' => t('Choose node view modes to count views of node.'),
    '#type' => 'checkboxes',
    '#options' => $options_node_view_modes,
    '#default_value' => $nodeviewcount_node_view_modes,
  );
  // Get user roles options.
  $options_user_roles = node_view_count_get_user_roles();
  $form['nodeviewcount_user_roles'] = array(
    '#title' => t('User roles'),
    '#description' => t('Choose user roles to count node views for.'),
    '#type' => 'checkboxes',
    '#options' => $options_user_roles,
    '#default_value' => variable_get('nodeviewcount_user_roles', array()),
  );
  // Get excluded user roles.
  $form['nodeviewcount_excluded_roles'] = array(
    '#title' => t('Excluded user roles'),
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $options_excluded_user_roles = node_view_count_get_excluded_user_roles();
  $form['nodeviewcount_excluded_roles']['nodeviewcount_excluded_user_roles'] = array(
    '#description' => t('Choose user roles which will be excluded from counting node views.'),
    '#type' => 'checkboxes',
    '#options' => $options_excluded_user_roles,
    '#default_value' => variable_get('nodeviewcount_excluded_user_roles', array()),
  );
  // Get user roles options.
  $options_user_roles = node_view_count_get_user_roles();
  $form['nodeviewcount_user_roles'] = array(
    '#title' => t('User roles'),
    '#description' => t('Choose user roles to count node views for.'),
    '#type' => 'checkboxes',
    '#options' => $options_user_roles,
    '#default_value' => variable_get('nodeviewcount_user_roles', array()),
  );
  // Get ways of counting options.
  $options_way_counting = array(
    NODEVIEWCOUNT_PHP_WAY_COUNT_VIEWS => t('PHP way'),
    NODEVIEWCOUNT_JS_WAY_COUNT_VIEWS => t('JS way'),
  );
  $form['nodeviewcount_way_counting'] = array(
    '#title' => t('Ways of counting'),
    '#description' => t('Choose a way how the module should count node view. If you want count node views anonymous users you must choose JS way.'),
    '#type' => 'radios',
    '#options' => $options_way_counting,
    '#default_value' => variable_get('nodeviewcount_way_counting', NODEVIEWCOUNT_PHP_WAY_COUNT_VIEWS),
  );
  $options_flush_log_timer = array(
    0 => t('Never'),
  );
  $time_intervals = array(
    0,
    86400,
    604800,
    1209600,
    2592000,
    15552000,
    31536000,
  );
  $options_flush_log_timer += drupal_map_assoc($time_intervals, 'format_interval');
  $cron_path = array(
    '@cron' => url('admin/reports/status'),
  );
  $form['nodeviewcount_flush_log_timer'] = array(
    '#type' => 'select',
    '#title' => t('Discard node views logs older than'),
    '#default_value' => variable_get('nodeviewcount_flush_log_timer', 0),
    '#options' => $options_flush_log_timer,
    '#description' => t('Older log entries will be automatically discarded, (Requires a correctly configured <a href="@cron">cron maintenance task</a>.). Pick Never if you dont want logs to be deleted.', $cron_path),
  );
  $form['#submit'][] = 'nodeviewcount_admin_settings_form_submit';
  $form = system_settings_form($form);
  return $form;
}

function nodeviewcount_admin_settings_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  $couting_user_roles = $values['nodeviewcount_user_roles'];
  foreach ($couting_user_roles as $key => $couting_user_role) {
    if ($couting_user_role) {
      $form_state['values']['nodeviewcount_excluded_user_roles'][$key] = 0;
    }
  }
}

/**
 * Get node types options.
 *
 * @return array
 *   Associative array of all node types.
 */
function nodeviewcount_get_checked_node_types() {
  $options = array();
  // All node types on site.
  $node_field_types = node_type_get_types();
  foreach ($node_field_types as $node_field_type) {
    $options[$node_field_type->type] = $node_field_type->name;
  }
  return $options;
}

/**
 * Get user roles options.
 *
 * @return array
 *   Associative array of user roles.
 */
function node_view_count_get_user_roles() {
  $options = array();
  //Excluded user roles.
  $excluded_user_roles = variable_get('nodeviewcount_excluded_user_roles', array());
  // All user roles on site.
  $user_roles = user_roles();
  foreach ($user_roles as $key => $user_role) {
    if (empty($excluded_user_roles[$key])) {
      $options[$key] = $user_role;
    }
  }
  return $options;
}

/**
 * Get excluded user roles options.
 *
 * @return array
 *   Associative array of user roles.
 */
function node_view_count_get_excluded_user_roles() {
  $options = array();
  // Counting user roles.
  $counting_user_roles = variable_get('nodeviewcount_user_roles', array());
  // All user roles on site.
  $user_roles = user_roles();
  foreach ($user_roles as $key => $user_role) {
    if (empty($counting_user_roles[$key])) {
      $options[$key] = $user_role;
    }
  }
  return $options;
}

/**
 * Get node view modes options.
 *
 * @return array
 *   Associative array of node view modes.
 */
function nodeviewcount_get_node_view_modes() {
  $options = array();
  $node_info = entity_get_info('node');
  $node_view_modes = $node_info['view modes'];
  foreach ($node_view_modes as $key => $node_view_mode) {
    $options[$key] = $node_view_mode['label'];
  }
  return $options;
}
