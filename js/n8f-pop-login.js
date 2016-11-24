(function($) {

    //Prep all the keys and functions to disable scroll with the modal opens
    //http://output.jsbin.com/xatidu/4/
    var keys = { 37: 1, 38: 1, 39: 1, 40: 1 };

    function preventDefault(e) {
        e = e || window.event;
        if (e.preventDefault)
            e.preventDefault();
        e.returnValue = false;
    }

    function preventDefaultForScrollKeys(e) {
        if (keys[e.keyCode]) {
            preventDefault(e);
            return false;
        }
    }

    function disableScroll() {
        if (window.addEventListener) // older FF
            window.addEventListener('DOMMouseScroll', preventDefault, false);
        window.onwheel = preventDefault; // modern standard
        window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
        window.ontouchmove = preventDefault; // mobile
        document.onkeydown = preventDefaultForScrollKeys;
    }

    function enableScroll() {
        if (window.removeEventListener)
            window.removeEventListener('DOMMouseScroll', preventDefault, false);
        window.onmousewheel = document.onmousewheel = null;
        window.onwheel = null;
        window.ontouchmove = null;
        document.onkeydown = null;
    }


    $(function() {
        $("#n8f-popup-login-message").tabs();
    });


    //Get Window Screen Width

    var screenWidth, screenHeight, dialogWidth, dialogHeight, isDesktop;

    screenWidth = window.screen.width;
    screenHeight = window.screen.height;

    if ( screenWidth < 500 ) {
        dialogWidth = screenWidth * .95;
        dialogHeight = screenHeight * .95;
    } else {
        dialogWidth = 500;
        dialogHeight = 500;
        isDesktop = true;
    }

    setTimeout(function(){
      $(function() {
          $("#n8f-popup-login-message").dialog({
              title: 'Welcome to Startup Academy!',
              draggable: false,
              modal: true,
              height: dialogHeight,
              width: dialogWidth,
              closeOnEscape: false,
              open: function() {
                if(isDesktop) {
                  disableScroll();
                }
              }
          });

      });
    }, 2000);





    $(document).ready(function() {

        //Register the visitor

        $("#popup-submit-register").on('click', function(event) {
            event.preventDefault();
            var regEmail = $('#popup-email-register').val();
            console.log('Value: ' + regEmail);

            var $apiURL, $inputParams;

            $apiURL = 'https://www.startupacademy.org/wp-content/plugins/membermouse/api/request.php?q=/createMember';

            $inputParams = "apikey=jpuqzijsv9&apisecret=jqsdfh90gg&";
            $inputParams += "email=" + regEmail + "&";
            $inputParams += "membership_level_id=3&";

            $.ajax({
                method: "POST",
                url: $apiURL,
                data: $inputParams

            }).done(function(res) {
                var res = JSON.parse(res);
                var resCode = res['response_code'];
                var resData = res['response_data'];

                if (resCode === '200') {

                    console.log('success');
                    $('#tabs-1 p.status').text('Getting you registered and logged in.');
                    $('form#login #username').val(resData.username);
                    $('form#login #password').val(resData.password);
                    $('form#login').trigger("submit");
                }
            }).fail(function(res) {
                console.log('Failed request');
            });

        });



        var loginUser = function(username, password) {
            $('form#login p.status').show().text(ajax_login_object.loadingmessage);
            console.log('Got these: ' + username, password);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                    'username': $('form#login #username').val(),
                    'password': $('form#login #password').val(),
                    'security': $('form#login #security').val()
                },
                success: function(data) {
                    $('form#login p.status').text(data.message);
                    if (data.loggedin == true) {
                        document.location.href = ajax_login_object.redirecturl;
                    }
                }
            });
            e.preventDefault();
        };

        // Perform AJAX login on form submit
        $('form#login').on('submit', function(e) {
            $('form#login p.status').show().text(ajax_login_object.loadingmessage);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                    'username': $('form#login #username').val(),
                    'password': $('form#login #password').val(),
                    'security': $('form#login #security').val()
                },
                success: function(data) {
                    $('form#login p.status').text(data.message);
                    if (data.loggedin == true) {
                        document.location.href = ajax_login_object.redirecturl;
                    }
                }
            });
            e.preventDefault();
        });


    });

}(jQuery));
