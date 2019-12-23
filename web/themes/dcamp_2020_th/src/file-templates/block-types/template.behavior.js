(function ($, Drupal) {
  Drupal.behaviors.blockType[COMPONENT_NAME] = {

    attach: function (context, settings) {
      console.log('Block type "[COMPONENT_NAME]"');
    }
  };

})(jQuery, Drupal);
