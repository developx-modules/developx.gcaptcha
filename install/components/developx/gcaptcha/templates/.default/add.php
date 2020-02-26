<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?
global $APPLICATION;
$APPLICATION->AddHeadString('<script type="text/javascript" src="//www.google.com/recaptcha/api.js?render=' . $arResult['OPTIONS']["CAPTCHA_KEY"] . '"></script>', true);
$APPLICATION->AddHeadScript($templateFolder . "/gcaptcha.js", true);
if ($arResult['OPTIONS']['INCLUDE_JQUERY'] == 'Y') {
    CJSCore::Init(["jquery"]);
} ?>
<script>
    $(document).ready(function ($) {
        if (typeof DevelopxGcaptcha_ == "undefined") {
            DevelopxGcaptcha_ = new DevelopxGcaptcha('<?=$arResult['OPTIONS']['CAPTCHA_KEY']?>', '<?=$arResult['CAPTCHA_ACTION'] ?>');
        }
    });
</script>