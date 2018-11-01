<?php

namespace Drupal\rp_slider\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'slider_widget' widget.
 *
 * @FieldWidget(
 *   id = "nouislider_widget",
 *   module = "rp_slider",
 *   label = @Translation("noUiSlider field"),
 *   field_types = {
 *     "integer",
 *   "decimal",
 *   "float"
 *   }
 * )
 */
class NouisliderWidget extends WidgetBase {


public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : NULL;
    $field_settings = $this->getFieldSettings();

    $element += [
      '#type' => 'number',
      '#default_value' => $value,
      '#min' => $field_settings['min'],
      '#max' => $field_settings['max'],
      '#suffix' => '<div class="slider shor slider-material-orange" id="slider"></div>',
      '#attributes' => array('class' => array('rating-input hidden')),
      '#attached' => array(
        'library' => array(
          'rp_slider/nouislider',
        ),
      ),
    ];
             
    return ['value' => $element];

  }


}
