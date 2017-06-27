<?php

namespace Drupal\rp_graph\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RpGraphController extends ControllerBase {

  public function info() {
    $build = array();
    $build['content'] = array(
      '#markup' => '<p>Visualization contains test data.</p>',
    );
    $build['#attached']['library'][] = 'rp_graph/sigmajs';
    return $build;
  }

}
