(function($) {
    "use strict";

    if (n8f_popup_submit_info.lesson_popup_active) {

    $( function() {
        var dialog, form, fauxMarkComplete;

        $( "#learndash_mark_complete_button" ).hide();

        fauxMarkComplete = '<input type="submit" value="Mark Complete" id="faux_learndash_mark_complete_button" class="ui-button ui-widget ui-state-default ui-corner-all" role="button">';

        $('form#sfwd-mark-complete').prepend(fauxMarkComplete);


        dialog = $( "#n8f-gif-popup" ).dialog({
          autoOpen: false,
          height: 550,
          width: 800,
          modal: true,
          buttons: {
            // "Create an account": addUser,
            "Next Lesson" : function() {
              dialog.dialog( "close" );
              $( "#learndash_mark_complete_button" ).show();
              $('#faux_learndash_mark_complete_button').hide();
              $( "#learndash_mark_complete_button" ).trigger('click');
            }
          },
          close: function() {
              console.log('close button');
          }
        });

        $( "#faux_learndash_mark_complete_button" ).button().on( "click", function(event) {
          event.preventDefault();
          dialog.dialog( "open" );
        });


      });

  } //end if statement

})(jQuery);
