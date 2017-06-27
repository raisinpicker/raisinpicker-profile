<?php

namespace Drupal\rp_d3_timeline\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class RpD3TimelineController extends ControllerBase {

  public function info() {
    $build = array();
    $build['content'] = array(
      '#markup' => '<p>Visualization contains test data.</p>
      <div id="d3timeline"></div>',
    );
    $build['#attached']['library'][] = 'rp_d3_timeline/d3timeline';
    return $build;
  }

}
