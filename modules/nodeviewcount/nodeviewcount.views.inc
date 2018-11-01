<?php

/**
 * @file
 * Provide views data for nodeviewcount.module.
 */

/**
 * Implements hook_views_data_alter().
 *
 * Alter DB tables defined via hook_views_data().
 */
function nodeviewcount_views_data_alter (array &$data) {
  $data['nodeviewcount']['table']['group'] = t('Node count');
  $data['nodeviewcount']['table']['base'] = [
    'field' => 'nid',
    'title' => t('Node view count'),
    'help' => t('This table is containing the data about count view of node.'),
    'weight' => -10,
  ];
  $data['nodeviewcount']['table']['join'] = [
    'node_field_data' => [
      'left_field' => 'nid',
      'field' => 'nid',
    ],
  ];
  $data['nodeviewcount']['nid'] = [
    'title' => t('Node id'),
    'field' => [
      'title' => t('Node id'),
      'help' => t('Node id'),
      'id' => 'node_view_count_node_id',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];
  $data['nodeviewcount']['id'] = [
    'title' => t('ID'),
    'field' => [
      'title' => t('Id'),
      'help' => t('View count id.'),
      'id' => 'node_view_count_id',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],

  ];
  $data['nodeviewcount']['datetime'] = [
    'title' => t('Timestamp '),
    'help' => t('When user view of node.'),
    'field' => [
      'id' => 'date',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'date',
    ],
  ];
  $data['nodeviewcount']['uid'] = [
    'title' => t('Who view(id)'),
    'help' => t('Who view  of node.'),
    'relationship' => [
      'base' => 'users_field_data',
      'base field' => 'uid',
      'id' => 'standard',
      'label' => t('Node View Count User'),
    ],
    'field' => [
      'id' => 'numeric',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];
  return $data;
}