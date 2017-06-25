(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.rp_slider_nouislider = {
    attach: function () {

    $(document).ready(function () {

      var slider = document.getElementById('slider');
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