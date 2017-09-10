<?php
/**
 * @file
 * Contains \Drupal\rp_finder\Form\FinderForm.
 */
namespace Drupal\rp_finder\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
class finderForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rp_finder_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['node'] = [
      '#type' => 'entity_autocomplete',
      '#title' => t('Start writing node title'),
      '#target_type' => 'node'
 /**      '#selection_settings' => array(
    'target_bundles' => array('article', 'page'),
  ),
      '#autocomplete_route_name' => 'view.node_autocomplete_reference'
    */
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Go'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirectUrl('/home');
    return;
  }
}