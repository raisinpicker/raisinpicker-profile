<?php

function template_preprocess_rp_d3(&$vars) {
  if (!empty($vars['view'])) {
    $view = $vars['view'];
    $options = $view->style_plugin->options;
    $chartdata[] = explode(',', $options['chart_axis_mapping']);
    $data = $view->style_plugin->get_render_fields();
    for($i = 0; $i < count($data); $i++) {
      foreach($data[$i] as $key => $value) {
        $chartdata[$i+1][] = trim(strip_tags($value->__toString()));
      }
    }
    $chartdata = json_encode($chartdata, JSON_NUMERIC_CHECK);
    $vars['id'] = $view->storage->id() . '_' . $view->current_display;
    $vars['metadata'] = $options;
    $vars['jsondata'] = $chartdata;
  }
}
