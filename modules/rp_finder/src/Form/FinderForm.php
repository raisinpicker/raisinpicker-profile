<?php
/**
 * @file
 * Contains \Drupal\rp_finder\Form\FinderForm.
 */
namespace Drupal\rp_finder\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
// use Drupal\Core\Ajax\AjaxResponse;

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
  	  '#target_type' => 'node',
  	  '#attributes' => [
	    'placeholder' => 'start writing...',
	  ],
    ];
	$form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Go there'),
      '#button_type' => 'primary',
    );


  

    return $form;
  }


/*

  	$form_state->setCached(FALSE);
    $form['node'] = [
      '#type' => 'entity_autocomplete',
  	  '#target_type' => 'node',
  	  '#attributes' => [
	    'placeholder' => 'start writing...',
	  ],
	  '#ajax' => [
	    'callback' => '::sayHello',
	     'effect' => 'fade',
	    'event' => 'change',
	  ],
    ];
  	$form['output'] = [
	  '#type' => 'textfield',
	  '#title' => t('Start writing node title'),
	  '#size' => '60',
	  '#disabled' => TRUE,
	  '#value' => 'Hello, World!',
	  '#attributes' => [
	    'id' => 'edit-output',
	  ],
	];

    $form['node2'] = [
      '#type' => 'textfield',
	  '#ajax' => [
	    'callback' => '::sayHello',
	     'effect' => 'fade',
	    'event' => 'change',
	  ],
    ];
  public function sayHello4(array $form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    if ($form_state->getValue('node'))  {
      $text = 'User or Email is exists';
    } else {
      $text = 'User or Email does not exists';
    }
    $ajax_response->addCommand(new HtmlCommand('#edit-output', $text));
    return $ajax_response;
  }

	public function sayHello(array $form, FormStateInterface $form_state) {
		return $form_state->setSubmitted();
	}

	public function sayHello2(array &$form, FormStateInterface $form_state) {
	    $node =  $form_state->getValue('node');
	      ksm($node);
	      drupal_set_message('test');

	}
*/


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  	$node = $form_state->getValue('node');
  	if (isset($node)) {
	    $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $form_state->getValue('node')]);
	    return $form_state->setRedirectUrl($url);
  	}
  	else {
  		return $form_state;
  	}
  }
}
