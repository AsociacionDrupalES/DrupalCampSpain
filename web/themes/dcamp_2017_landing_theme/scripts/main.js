(function ($, Drupal) {

  Drupal.behaviors.dcamp2017LandingThemeBehaviors = {
    attach: function (context, settings) {

      /**
       * Full page functionality provided by the fullPage.js library.
       */
      $('#fullpage').fullpage(
        {
          css3: true,
          navigationPosition: 'right',
          navigation: true,
          responsiveWidth: 960,
          responsiveHeight: 700,
          scrollBar: true,
          afterRender: function () {
            if ($('img.d').length > 0) {
              $('img.d').plaxify({"xRange": 40, "yRange": 40});
              $('img.r').plaxify({"xRange": 20, "yRange": 20});
              $('img.u').plaxify({"xRange": 10, "yRange": 10, "invert": true});
              $('img.p').plaxify({"xRange": 40, "yRange": 40, "invert": true});
              $('img.al').plaxify({"xRange": 30, "yRange": 30});
              $.plax.enable();
            }
          }
        }
      );

      $('.scroll-down').bind(
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

      $("#countdown").countdown({
        htmlTemplate: '<div id="countdown_day" class="countdown count"><div><span>%d ' + Drupal.t('Days') + '</span></div></div><div id="coundown_hour" class="countdown count"><div><span>%h ' + Drupal.t('Hours') + '</span></div></div><div id="coundown_min" class="countdown count"><div><span>%i '+Drupal.t('Minutes')+'</span></div></div><div id="coundown_sec" class="countdown count c_last"><div><span>%s '+Drupal.t('Seconds')+'</span></div></div>',
        date: $('#countdown').data('date'),
        hoursOnly: false,
        leadingZero: true
      });

      /**
       * Hand made Parallax effect for background images.
       */
      var depthCoeficient = 80;
      $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var screenHeight = $(window).height();
        $('.section:not(#intro)').each(function (index) {
          var top = $(this).offset().top;
          var transitionCoeficient = 100 - ((scrollTop - top) / screenHeight) * depthCoeficient;
          $(this).css('background-position', '50% ' + transitionCoeficient + '%');
        });
      });

    }
  };
})(jQuery, Drupal);