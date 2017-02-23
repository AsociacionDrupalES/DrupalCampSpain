(function ($, Drupal) {

  Drupal.behaviors.dcamp2017ThemeFrontpageBehaviors = {
    attach: function (context, settings) {

      $(window).load(function(){
        setHeightIntro();
      });
      $(window).resize(function(){
        setHeightIntro();
      });
      setHeightIntro();

      function setHeightIntro(){
        var adminMenuHeight = $('#toolbar-bar').outerHeight();
        var bottomAreaHeight = $('#intro .bottom-area').outerHeight();
        var headerHeight = $('header').height();
        var screenHeight = $(window).height();
        $('#intro .top-area').css('height', screenHeight-headerHeight -bottomAreaHeight - adminMenuHeight + 'px');
      }

      $('img.d').plaxify({"xRange": 40, "yRange": 40});
      $('img.r').plaxify({"xRange": 20, "yRange": 20});
      $('img.u').plaxify({"xRange": 10, "yRange": 10, "invert": true});
      $('img.p').plaxify({"xRange": 40, "yRange": 40, "invert": true});
      $('img.al').plaxify({"xRange": 30, "yRange": 30});
      $.plax.enable();
      
      // Avoid scroll on google maps.
      $('.maps').click(function () {
        $('.maps iframe').css("pointer-events", "auto");
      });

      $( ".maps" ).mouseleave(function() {
        $('.maps iframe').css("pointer-events", "none");
      });
    }
  };
})(jQuery, Drupal);