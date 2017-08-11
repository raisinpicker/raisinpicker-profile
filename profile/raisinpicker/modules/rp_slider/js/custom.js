(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.rp_slider_nouislider = {
    attach: function (context) {
//    $(document).ready(function () {
      $(context).find("#slider").once('myCustomBehavior').each(function(){
//      var slider = document.getElementById('slider');
      var slider = $("#slider").get(0);      
      noUiSlider.create(slider, {
        start: 4,
        connect: true,
        step: 1,
        tooltips: true,
        format: {
          to: function ( value ) {
          return Math.round(value);
          },
          from: function ( value ) {
          return value;
          }
        },
        animationDuration: 300,
        range: {
          'min': 1,
          'max': 9
        }
      },true);
      var inputNumber = document.getElementsByClassName('rating-input')[0];
      slider.noUiSlider.on('update', function(value) {
        inputNumber.value = value;
        slider.setAttribute("value",value);    
      });
      /*
      inputNumber.addEventListener('change', function(){
        slider.noUiSlider.set(this.value);
      });
      */
      });  
    }
  };
})(jQuery, Drupal);