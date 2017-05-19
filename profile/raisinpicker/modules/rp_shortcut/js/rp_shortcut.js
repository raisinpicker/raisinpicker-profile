/**
 * @file
 * JavaScript file for the cache_clear_shortcut module.
 */

(function ($, Drupal) {
  // Remap the filter functions for autocomplete to recognise the
  // extra value "command".
  'use strict';

  Drupal.behaviors.rp_shortcut = {
    attach: function () {

    Mousetrap.bind('4', function() { console.log('keystroke 4'); });
    Mousetrap.bind('5', function() { window.location = Drupal.url('node/add/knowledge'); });



/*      
      $('body').once('cache_clear_shortcut').each(function () {
        // Key events.
        $(document).keydown(function (event) {
          if (event.altKey === true && event.keyCode === 67) {
            $('body').prepend(imageUrl);
            $.ajax({
              url: Drupal.url('admin/config/development/performance/clearcache'),
              dataType: 'json',
              success: function (data) {
                $('.cache-load').hide();
                $('body').prepend('<div class="overlay"><div class="popup"><h2>Cache cleared.</h2><a class="close" href="#">&times;</a></div></div>');
                $('.overlay .close').click(function () {
                  $('.overlay').fadeOut();
                });
                setTimeout(function () {
                  $('.overlay').fadeOut();
                }, 1000);
              }
            });
          }
        });
      });
*/
    }
  };

})(jQuery, Drupal);
