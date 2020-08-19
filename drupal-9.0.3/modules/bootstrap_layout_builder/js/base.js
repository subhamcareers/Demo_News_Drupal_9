/**
 * @file
 * Behaviors Bootstrap Layout Builder general scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";
  
  // Configure Section.
  Drupal.behaviors.bootstrapLayoutBuilderConfigureSection = {
    attach: function (context) {

      // Our Tabbed User Interface.
      $("#blb_nav-tabs li a", context).once('blb_nav-tabs').on('click', function () {
        $('#blb_nav-tabs li a').removeClass('active');
        $(this).toggleClass('active');
        var href = $(this).attr('data-target');

        if(href && $('#blb_tabContent').length) {
          $('.blb_tab-pane').removeClass('active');
          $('.blb_tab-pane--' + href).addClass('active');
        }
      });

      // Custom solution for Bootstrap 3 Drupal theme.
      $('input.blb_container_type', context).each(function() {
        var checked = $(this).prop("checked");
        if (typeof checked !== typeof undefined && checked !== false) {
          $(this).parent('label').addClass('active');
        }
      });

      // Custom solution for Bootstrap 3 & Bario Drupal themes.
      $('.blb_container_type .fieldset-wrapper label', context).on('click', function () {
        $(this).parents('.fieldset-wrapper').find('label').removeClass('active');
        $(this).parents('.fieldset-wrapper').find('input').prop("checked", false);
        $(this).parent().find('input').prop('checked', true);
        $(this).addClass('active');
      });

      // Graphical Layout Columns
      $('.blb_breakpoint_cols', context).each(function () {
        const numOfCols = 12;
        // .custom-control, .custom-radio to solve Bario issues.
        $(this).find('.form-item, .custom-control, .custom-radio').each(function () {
          var cols = $(this).find('input').val().replace('blb_col_', '');
          var colsConfig = cols.split('_');
          var colsLabel = $(this).find('label');
          var col_classes = 'blb_breakpoint_col';
          var checked = $(this).find('input').prop("checked");
          if (typeof checked !== typeof undefined && checked !== false) {
            col_classes += ' bp-selected';
          }

          // Wrap our radio labels and display as a tooltip.
          colsLabel.wrapInner('<div class="blb_tooltip blb_tooltip-lg"></div>');

          // Provide a graphical representation of the columns via some nifty divs styling.
          $.each(colsConfig, function(index, value) {
            var width = ((value / numOfCols) * 100);
            $('<div />', {
              'text': width.toFixed(0) + '%',
              'style': 'width:' + width + '%;',
              'class': col_classes,
            })
            .appendTo(colsLabel)
            .on('click', function () {
              $(this).parents('.blb_breakpoint_cols').find('.blb_breakpoint_col').removeClass('bp-selected');
              $(this).parents('.blb_breakpoint_cols').find('input').prop("checked", false);
              $(this).parents('label').parent().find('input').prop("checked", true);
              $(this).parents('label').find('.blb_breakpoint_col').addClass('bp-selected');
            });

          });
        });

      });

      $(".bootstrap_layout_builder_bg_color input:radio", context).once('blb_bg-color').each(function () {
        $(this).next('label').addClass($(this).val());
      });

      // Custom solution for bootstrap 3 & Bario drupal theme issues.
      $(".bootstrap_layout_builder_bg_color .fieldset-wrapper input:radio", context).each(function () {
        $(this).parents('.radio').find('label').addClass($(this).val());
        var checked = $(this).prop("checked");
        if (typeof checked !== typeof undefined && checked !== false) {
          $(this).parents('.radio').find('label').addClass('active');
        }
      });

      $(".bootstrap_layout_builder_bg_color .fieldset-wrapper label", context).on('click', function () {
        $(this).parents('.fieldset-wrapper').find('label').removeClass('active');
        $(this).parents('.fieldset-wrapper').find('input').prop("checked", false);
        $(this).parent().find('input').prop('checked', true);
        $(this).addClass('active');
      });
    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
