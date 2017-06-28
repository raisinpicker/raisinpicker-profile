<?php

namespace Drupal\rp_calendar\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class RpCalendarController extends ControllerBase {

  public function info() {
    $build = array();
    $build['content'] = array(
      '#markup' => '<div id="calendar_basic" style="width: 1000px; height: 650px;"></div>',
    );
    $build['#attached']['library'][] = 'rp_calendar/googlecalendar';
    return $build;
  }

}
