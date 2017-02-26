(function ($, Drupal) {

  Drupal.behaviors.dcamp2017ThemeBehaviors = {
    attach: function (context, settings) {
      $('.main-menu-trigger').click(function () {
        $('body').addClass('main-menu-enabled');
      });
      $('.close-trigger').click(function () {
        $('body').removeClass('main-menu-enabled');
      });

      $('#block-userlogin').prepend('<div class="user-login-trigger">'+Drupal.t('Login')+'</div>');

    }
  };
})(jQuery, Drupal);