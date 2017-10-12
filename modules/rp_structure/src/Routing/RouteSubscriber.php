<?php

namespace Drupal\rp_structure\Routing;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber.
 *
 * @package Drupal\rp_structure\Routing
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {

    /*   routes to be shown in front theme */
    $term_routes = [
      'entity.taxonomy_term.add_form',
      'entity.taxonomy_vocabulary.overview_form',
      'entity.entity_form_display.taxonomy_term.default',
      'entity.entity_view_display.taxonomy_term.default',
      'entity.user.edit_form'
    ];
    foreach ($collection->all() as $name => $route) {
      if (in_array($name, $term_routes)) {
        $route->setOption('_admin_route', FALSE);
      }
    }   
  }
}