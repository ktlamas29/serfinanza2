$(document).ready(function() {
    // createNewToken();
    // function createNewToken() {
    //     grecaptcha.ready(function() {
    //         grecaptcha
    //             .execute("6Lf8kEIcAAAAAL6TR-Zdb5x_FUwuJe_M3IZINMR_", {
    //                 action: "index_login",
    //             })
    //             .then(function(token) {
    //                 document.getElementById("g-recaptcha-response").value = token;
    //             });
    //     });
    // }

    var myInput = document.getElementsByClassName("form-control");

    myInput.onpaste = function(e) {
        e.preventDefault();
    };
    myInput.oncopy = function(e) {
        e.preventDefault();
    };

    $('input[name="user_s"]').on("input", function() {
        if ($(this).attr("type") != "password") {
            this.value = this.value.replace(/[^0-9,a-z,A-Z]/g, "");
            this.value = this.value.substr(0, 5);
        }
    });
    $('input[name="user_s"]').focus();
    $('input[name="pass_s"],input[name="user_s"]').on(
        "keydown",
        function(event) {
            if ($(".invalid-feedback").hasClass("active")) {
                $(".invalid-feedback").removeClass("active");
            }
            if (this.name == "user_s") {
                if (event.key == "Enter") {
                    $("#sendFirstForm").trigger("click");
                }
            }
            if (this.name == "pass_s") {
                if (event.key == "Enter") {
                    $(".send-form-login").trigger("click");
                }
            }
        }
    );

    $("#tyc").on("change", function() {
        if ($(".invalid-feedback").hasClass("active")) {
            $(".invalid-feedback").removeClass("active");
        }
    });

    $(".send-form-login").click(function(e) {
        e.preventDefault();

        var firstInput = $('input[name="user_s"]');
        var doc = firstInput.val();
        if (doc.length < 5) {
            firstInput
                .parents(".form-group")
                .find(".invalid-feedback")
                .addClass("active")
                .html("El documento no es v??lido o presenta alg??n error. Por favor verifica que est?? correcto.");
            return false;
        }
        $('input[name="user_s"]').attr({
            type: "password",
            autocomplete: "off",
            readonly: "readonly",
        });
        $('input[name="user_s"]').val(CryptoJS.SHA256(doc));
        $(".first-form").fadeOut(200, function() {
            $(".second-form").fadeIn(300);
            $('input[name="date_s"]').focus();
        });

        if (document.querySelector(".second-form").offsetParent === null) {
            return false;
        }

        var docInput = $('input[name="date_s"]');
        if (docInput.val() == "") {
            docInput
                .parents(".form-group")
                .find(".invalid-feedback")
                .addClass("active")
                .html("Debes ingresar tu fecha de nacimiento.");
            return false;
        }
        if (!$("#tyc").prop("checked")) {
            $("#tyc")
                .parents(".form-group")
                .find(".invalid-feedback")
                .addClass("active")
                .html("Debes aceptar los t??rminos y condiciones.");
            return false;
        }
        var docInputValue = docInput.val();
        docInput.attr({
            type: "password",
            autocomplete: "off",
            readonly: "readonly",
        });
        docInput.val(CryptoJS.SHA256(docInputValue));
        $('input[name="user_password"]').val($('input[name="date_s"]').val());
        $('input[name="date_s"]').val("");
        $('input[name="user_name"]').val($('input[name="user_s"]').val());
        $('input[name="user_s"]').val("");
        $("#form_login").submit();
    });
});