/**
 * @file
 * Nodeviewcount statistics functionality.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  $(document).ready(function () {
    $.each(drupalSettings.nodeviewcount.data, function (key, value) {
      $.ajax({
        type: 'POST',
        cache: false,
        url: drupalSettings.nodeviewcount.url,
        data: value
      });
    });
  });
})(jQuery, Drupal, drupalSettings);
