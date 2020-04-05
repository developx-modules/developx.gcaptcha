<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<input type="hidden" name="token" class="dxCaptchaToken">
<script>
    $(document).ready(function ($) {
        if (typeof DevelopxGcaptcha_ !== "undefined") {
            DevelopxGcaptcha_.resetCaptcha();
        }
    });
</script>