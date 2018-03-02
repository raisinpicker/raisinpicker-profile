<?php

namespace Drupal\rp_chatbot\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;


/**
 * Plugin implementation of chatbot intent.
 *
 * @Intent(
 *   id = "HelloWorld",
 *   label = @Translation("Hello World!")
 * )
 */
class HelloWorldIntent extends IntentPluginBase {

  /**
   * {@inheritdoc}
   */
  public function process() {
    $this->response->setIntentResponse('Hello world.');
    $this->response->setIntentDisplayCard('Hi to everyone.', 'Greetings.');
  }

}
