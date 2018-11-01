<?php

function nodeviewcount_views_data() {
  $data = array();
  $data['nodeviewcount']['table']['group'] = t('Node count');
  $data['nodeviewcount']['table']['base'] = array(
    'field' => 'nid',
    'title' => t('Node view count'),
    'help' => t('This table is containing the data about count view of node.'),
    'weight' => -10,
  );
  $data['nodeviewcount']['table']['join'] = array(
    'node' => array(
      'handler' => 'views_join',
      'left_table' => 'node',
      'left_field' => 'nid',
      'field' => 'nid',
    ),
  );
  $data['nodeviewcount']['nid'] = array(
    'title' => t('Node id'),
    'help' => t('Node id.'),
    'relationship' => array(
      'base' => 'node',
      'field' => 'nid',
      'handler' => 'views_handler_relationship',
      'label' => t('Node View Count Node'),
    ),
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
    ),
  );
  $data['nodeviewcount']['uid'] = array(
    'title' => t('Who view'),
    'help' => t('Who view  of node.'),
    'relationship' => array(
      'base' => 'users',
      'field' => 'uid',
      'handler' => 'views_handler_relationship',
      'label' => t('Node View Count User'),
    ),
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
    ),
  );
  $data['nodeviewcount']['timestamp'] = array(
    'title' => t('Timestamp'),
    'help' => t('When user view of node.'),
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
  );
  return $data;
}
