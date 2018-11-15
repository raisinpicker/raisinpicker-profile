<?php

namespace Drupal\raisinpicker\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * Controller routines for page example routes.
 */
class RpPageController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'raisinpicker';
  }

  public function cheatsheet() {
    return [
      '#theme' => 'cheatsheet',
    ];
  }

  public function analytics() {
    return [
      '#theme' => 'analytics',
    ];
  }

  public function settings() {
    $user = \Drupal::currentUser()->id();
    $url = \Drupal::url('entity.user.edit_form', ['user' => $user]);
    return new RedirectResponse($url);
  }

}
