<?php

function template_preprocess_views_simplechart_graph(&$vars) {
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
    if (in_array($options['chart_type'],['timeline','calendar']) ) {
      $chartdata = preg_replace('/"(\d+)-(\d+)-(\d+)"/i', 'new Date(\'$1-$2-$3\')', $chartdata);
    }
    if(in_array($options['chart_type'],array('BarChart','ColumnChart')) && $options['chart_type_stacked'] == 'yes') {
      $vars['isStacked'] = 'yes';
    }
    $vars['id'] = $view->storage->id() . '_' . $view->current_display;
    $vars['metadata'] = $options;
    $vars['jsondata'] = $chartdata;
  }
}
