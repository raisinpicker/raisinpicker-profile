<?php

namespace Drupal\rp_tour\Controller;

/**
 * Controller routines for rp_tour routes.
 */
class RpTourController {

  /**
   * Outputs some content for testing tours.
   *
   * @param string $locale
   *   (optional) Dummy locale variable for testing routing parameters. Defaults
   *   to 'foo'.
   *
   * @return array
   *   Array of markup.
   */
  public function tour1($locale = 'foo') {
    return array(
      'tip-1' => array(
        '#type' => 'container',
        '#attributes' => array(
          'id' => 'tour-test-1',
        ),
        '#children' => t('Where does the rain in Spain fail?'),
      ),
      'tip-3' => array(
        '#type' => 'container',
        '#attributes' => array(
          'id' => 'tour-test-3',
        ),
        '#children' => t('Tip created now?'),
      ),
      'tip-4' => array(
        '#type' => 'container',
        '#attributes' => array(
          'id' => 'tour-test-4',
        ),
        '#children' => t('Tip created later?'),
      ),
      'tip-5' => array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array('tour-test-5'),
        ),
        '#children' => t('Tip created later?'),
      ),
      'code-tip-1' => array(
        '#type' => 'container',
        '#attributes' => array(
          'id' => 'tour-code-test-1',
        ),
        '#children' => t('Tip created now?'),
      ),
    );
  }
}
