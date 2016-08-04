(function ($, Drupal) {

  Drupal.behaviors.dcamp2017ThemeBehaviors = {
    attach: function (context, settings) {

      /**
       * Full page functionality provided by the fullPage.js library.
       */
      $('#fullpage').fullpage(
        {
          css3: true,
          navigationPosition: 'right',
          navigation: true,
          responsiveWidth: 980,
          responsiveHeight: 750,
          afterRender: function () {
            setTimeout(
              function () {
                $('img.d').plaxify({"xRange": 40, "yRange": 40});
                $('img.r').plaxify({"xRange": 20, "yRange": 20});
                $('img.u').plaxify({"xRange": 10, "yRange": 10, "invert": true});
                $('img.p').plaxify({"xRange": 40, "yRange": 40, "invert": true});
                $('img.al').plaxify({"xRange": 30, "yRange": 30});
                $.plax.enable();
              },
              100
            );

          }
        }
      );

      $('.arrow.flash').css('cursor', 'pointer').bind(
        'click',
        function () {
          $.fn.fullpage.moveSectionDown();
        }
      );

      /**
       * Countdown javascript
       *
       * @todo move this to Dcamp module.
       */

      var endDate = "may 24, 2017 23:59:59";

      $("#countdown").countdown({
        htmlTemplate: '<div id="countdown_day" class="countdown count"><div><span>%d Días</span></div></div><div id="coundown_hour" class="countdown count"><div><span>%h Horas</span></div></div><div id="coundown_min" class="countdown count"><div><span>%i Minutos</span></div></div><div id="coundown_sec" class="countdown count c_last"><div><span>%s Segundos</span></div></div>',
        date: endDate,
        hoursOnly: false,
        leadingZero: true
      });

    }
  };
})(jQuery, Drupal);