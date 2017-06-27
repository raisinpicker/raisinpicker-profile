<?php

namespace Drupal\rp_visjs_timeline\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class RpVisjsTimelineController extends ControllerBase {

  public function info() {
    $build = array();
    $build['content'] = array(
      '#markup' => '<p>Visualization contains test data.</p>
      <div id="mytimeline1"></div>',
    );
    $build['#attached']['library'][] = 'rp_visjs_timeline/visjstimeline';
    return $build;
  }

}
