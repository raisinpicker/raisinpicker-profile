<?php

namespace Drupal\rp_map\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class RpMapController extends ControllerBase {

  public function info() {
    $build = array();
    $build['content'] = array(
      '#markup' => '<div id="chart_div"></div>',
    );
    $build['#attached']['library'][] = 'rp_map/gmap';
    return $build;
  }

}
