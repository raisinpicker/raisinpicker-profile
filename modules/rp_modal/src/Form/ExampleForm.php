<?php

namespace Drupal\modal_form_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * ExampleForm class.
 */
class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {
    $form['open_modal'] = [
      '#type' => 'link',
      '#title' => $this->t('Open Modal'),
      '#url' => Url::fromRoute('modal_form_example.open_modal_form'),
      '#attributes' => [
        'class' => [
          'use-ajax',
          'button',
        ],
      ],
    ];

    // Attach the library for pop-up dialogs/modals.
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'modal_form_example_form';
  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['config.modal_form_example_form'];
  }

}