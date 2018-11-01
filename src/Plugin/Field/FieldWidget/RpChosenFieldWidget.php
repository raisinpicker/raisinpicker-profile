<?php

namespace Drupal\raisinpicker\Plugin\Field\FieldWidget;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsSelectWidget;

/**
 * Plugin implementation of the 'chosen_select' widget.
 *
 * @FieldWidget(
 *   id = "rp_chosen",
 *   label = @Translation("RP Chosen"),
 *   field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = TRUE
 * )
 */
class RpChosenFieldWidget extends OptionsSelectWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $element += array(
      '#chosen' => 1,    
    );

    $element_options = $element['#options'];
    $element['#options'] = array();
    
    foreach ($element_options as $value => $label) {
      // when $element_options item is an array, there are multiple vocabularies
      if (is_array($label)) {
        foreach ($label as $value2 => $label2) {  
          // Separates the label from the hierarchy symbols.
          preg_match("/^(?P<hierarchy_symbols>[\-\ ]*)\ ?(?P<item_string_clean>.*)/", $label2, $label_parsed);
          // Item depth.
          $item_depth = (int) substr_count($label_parsed['hierarchy_symbols'], '-');
          // Get the depths by symbol, of the item, if exist, and isn't a none value.
          if ($item_depth != 0 && $value2 != '_none') {
            // Set the new parent string path.
            $label2 = $parent_string[$item_depth] = $parent_string[$item_depth-1] . ' » ' . $label_parsed['item_string_clean'];
          }
          else {
            // Save the first level parent.
            $parent_string[0] = $label2;
          }
          $element['#options'][$value2] = $label2;
        }  
      }
      else { 
        // Separates the label from the hierarchy symbols.
        preg_match("/^(?P<hierarchy_symbols>[\-\ ]*)\ ?(?P<item_string_clean>.*)/", $label, $label_parsed);
        // Item depth.
        $item_depth = (int) substr_count($label_parsed['hierarchy_symbols'], '-');
        // Get the depths by symbol, of the item, if exist, and isn't a none value.
        if ($item_depth != 0 && $value != '_none') {
          // Set the new parent string path.
          $label = $parent_string[$item_depth] = $parent_string[$item_depth-1] . ' » ' . $label_parsed['item_string_clean'];
        }
        else {
          // Save the first level parent.
          $parent_string[0] = $label;
        }
        $element['#options'][$value] = $label;        
      }
    }

    return $element;
  }

}
