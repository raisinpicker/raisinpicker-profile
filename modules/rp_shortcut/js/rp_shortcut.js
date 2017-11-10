/**
 * @file
 * JavaScript file for the rp_shortcut module.
 */

(function ($, Drupal) {
  // Remap the filter functions for autocomplete to recognise the
  // extra value "command".
  'use strict';

  Drupal.behaviors.rp_shortcut = {
    attach: function () {
//    Mousetrap.bind('4', function() { console.log('keystroke 4'); });
    Mousetrap.bind('shift+i', function() { window.location = Drupal.url('index'); });
    Mousetrap.bind('shift+b', function() { window.location = Drupal.url('bulk'); });
    Mousetrap.bind('shift+s', function() { window.location = Drupal.url('s'); });
    Mousetrap.bind('shift+h', function() { window.location = Drupal.url('home'); });
    Mousetrap.bind('shift+k', function() { window.location = Drupal.url('node/add/knowledge'); });
    Mousetrap.bind('shift+u', function() { window.location = Drupal.url('node/add/publication'); });
    Mousetrap.bind('shift+p', function() { window.location = Drupal.url('node/add/person'); });
    Mousetrap.bind('shift+e', function() { window.location = Drupal.url('node/add/event'); }); 
    Mousetrap.bind('shift+l', function() { window.location = Drupal.url('node/add/place'); });               
    Mousetrap.bind('shift+o', function() { window.location = Drupal.url('node/add/object'); });
    Mousetrap.bind('shift+c', function() { window.location = Drupal.url('node/add/collection'); });
/**
    Mousetrap.bind('shift+s', function() {  });   // save form
    Mousetrap.bind('shift+left', function() {  });   // previous item   
    Mousetrap.bind('shift+right', function() {  });   // next item
*/
    }
  };

})(jQuery, Drupal);
