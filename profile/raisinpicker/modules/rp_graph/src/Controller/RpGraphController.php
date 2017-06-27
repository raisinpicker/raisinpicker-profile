<?php

namespace Drupal\rp_graph\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class RpGraphController extends ControllerBase {

  public function info() {
    $build = array();
    $build['content'] = array(
      '#markup' => '<div id="graph-container">
  <div id="notice" class="well">
    <h2>Wait for layout rendering...</h2>
  </div>
  </div>
</div>
<div id="control-pane" class="well">    
    <div>
      <p>No. of relations (<span id="min-degree-val">0</span>)
      0 <input id="min-degree" type="range" min="0" max="0" value="0"> <span id="max-degree-value">0</span></p>
    </div>
    <div>
      <p>Min. node rating (<span id="min-rating-val">0</span>)
      1 <input id="node-category" type="range" min="1" max="9" value="0"> 9</p>
    </div>
    <div>
      <button id="reset-btn">Reset filters</button>
    </div>
    <div id="dump" class="hidden"></div>
  </div>
</div>',
    );
    $build['#attached']['library'][] = 'rp_graph/sigmajs';
    return $build;
  }

}
