/**
 * @file
 * Contains JavaScript used in Zinble theme.
 */

(function ($) {
  'use strict';

  Drupal.behaviors.zinble = {
    attach: function (context, settings) {
      // Banner slider.
      $('.bannerSlider').slick({
        dots: false,
        autoplay: true,
        infinite: true,
        slidesToShow: 1,
        slideswToScroll: 1,
        arrows: false
      });

      // Partners slider.
      $('.partnerSlider').slick({
        dots: false,
        autoplay: true,
        infinite: true,
        slidesToShow: 5,
        slideswToScroll: 1,
        adaptiveHeight: true,
        arrows: false
      });

      // Add banner image as a background.
      if ($('#block-views-block-banner-block-1 img').length > 0) {
        var imageUrl = $('#block-views-block-banner-block-1 img').attr('src');
        $('.banner-wrapper .banner-layer').css('background-image', 'url(' + imageUrl + ')');
      }
    }
  };

}(jQuery));
