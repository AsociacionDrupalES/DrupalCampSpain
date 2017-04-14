(function ($, Drupal) {

  Drupal.behaviors.dcamp2017ThemeBehaviors = {
    attach: function (context, settings) {
      $('.main-menu__trigger').once().click(function () {
        $('body').toggleClass('main-menu-enabled');
      });

      $('#block-userlogin').prepend('<div class="user-login-trigger">'+Drupal.t('Login')+'</div>');

    }
  };
})(jQuery, Drupal);
