/**
 * @file
 * Behaviors of Boostrap Layout Builder local video background.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.bootstrapLayoutBuilderLocalVideoBG = {
    attach: function (context, settings) {
      // Set the height of the background video.
      $('.background-local-video').height(function() {
        console.log($(this).find('.video-content').height());
        return $(this).find('.video-content > div').outerHeight();
      });
    }
  }

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
