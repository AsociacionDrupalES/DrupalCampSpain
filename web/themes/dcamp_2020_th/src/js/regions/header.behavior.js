(function ($, Drupal) {
  Drupal.behaviors.dcamp2020ThRegionHeader = {

    attach: function (context, settings) {
      const $header = $('header');

      new Waypoint({
        element: $('body')[0],
        handler: function (direction) {

          if (direction === 'down') {
            $header.addClass('header-offset');
          }
          else {
            $header.removeClass('header-offset');
          }
        },
        offset: -100
      });

    }
  };

})(jQuery, Drupal);
