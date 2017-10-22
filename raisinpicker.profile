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
	$form['#submit'][] = 'raisinpicker_form_install_configure_submit';
}