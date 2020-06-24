$(document).ready(function ($) {
    if (window.DevelopxGcaptcha)
        return;
    window.DevelopxGcaptcha = function (captchaKey, captchaAction) {
        this.captchaKey = captchaKey;
        this.captchaAction = captchaAction;
        this.initCaptcha();
    };
    window.DevelopxGcaptcha.prototype = {
        initCaptcha: function () {
            var $this = this;
            if (
                typeof grecaptcha != 'undefined'
            ) {
                grecaptcha.ready(function () {
                    $this.setCaptcha();
                    setInterval(
                        function () {
                            $this.setCaptcha();
                        },
                        150000
                    );
                });
            } else {
                setTimeout(function () {
                    $this.initCaptcha();
                }, 500);
            }
        },
        setCaptcha: function () {
            var $this = this;
            grecaptcha.execute($this.captchaKey, {action: $this.captchaAction})
                .then(function (token) {
                    $this.setCookie('gToken', token, 1);
                });
        },
        setCookie: function (c_name, value, exdays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value = escape(value) + ((exdays == null) ? "" : ";  path=/; expires=" + exdate.toUTCString());
            document.cookie = c_name + "=" + c_value;
        }
    }
});