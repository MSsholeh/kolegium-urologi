// Class Definition
var DasterLogin = function () {
    var login = $('#kt_login');

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-bold alert-solid-' + type + ' alert-dismissible" role="alert">\
			<div class="alert-text">'+msg+'</div>\
			<div class="alert-close">\
                <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
            </div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        KTUtil.animateClass(alert[0], 'fadeIn animated');
    }

    // Private Functions
    var handleSignInFormSubmit = function () {
        $('#kt_login_signin_submit').click(function (e) {
            e.preventDefault();

            var btn = $(this);
            var form = $('#kt_login_form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            KTApp.progress(btn[0]);

            setTimeout(function () {
                KTApp.unprogress(btn[0]);
            }, 2000);

            // ajax form submit:  http://jquery.malsup.com/form/
            form.ajaxSubmit({
                url: form.attr('action'),
                success: function (response, status, xhr, $form) {
                    KTApp.unprogress(btn[0]);
                    // console.log(response);
                    showErrorMsg(form, 'success', 'Login Success!');
                    setTimeout(function() {
                        window.location = homeUrl;
                    }, 1000);
                    // console.log(response);
                },
                error: function (response) {
                    KTApp.unprogress(btn[0]);
                    if(response.status === 422){
                        showErrorMsg(form, 'danger', 'Incorrect username or password. Please try again.');
                    } else {
                        showErrorMsg(form, 'danger', 'Something Error!');
                    }
                }
            });
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleSignInFormSubmit();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function () {
    DasterLogin.init();
});
