<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? $frame = $this->createFrame()->begin(); ?>
    <?
    global $USER;
    if (
        !$USER->IsAuthorized() &&
        empty($_SESSION['CAPTCHA_CHECKED'])
    ) {
    ?>
        <script type="text/javascript" src="//www.google.com/recaptcha/api.js?render=<?=$arResult['OPTIONS']["CAPTCHA_KEY"]?>"></script>
        <script type="text/javascript" src="<?=$templateFolder?>/gcaptcha.js"></script>
        <script>
            $(document).ready(function ($) {
                if (typeof DevelopxGcaptcha_ == "undefined") {
                    DevelopxGcaptcha_ = new DevelopxGcaptcha('<?=$arResult['OPTIONS']['CAPTCHA_KEY']?>', '<?=$arResult['CAPTCHA_ACTION'] ?>');
                }
            });
        </script>
    <? } ?>
<? $frame->beginStub(); ?>
<? $frame->end(); ?>