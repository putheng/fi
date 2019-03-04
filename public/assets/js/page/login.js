$(document).ready(function () {
    
    //Square blue color scheme for iCheck

    $('input[type="checkbox"].square-blue').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    $("#signup").click(function() {
        $("#notific").remove();
    });

    $('#login_form').bootstrapValidator({
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'The username is required'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    }
                }
            }

        }
    });
    $("#reset_pw").bootstrapValidator({
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'A registered email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            }
        }
    });
});