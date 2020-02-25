<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<script>
    $(document).ready(function ($) {
        if (typeof DevelopxGcaptcha_ !== "undefined") {
            DevelopxGcaptcha_.resetCaptcha();
        }
    });
</script>