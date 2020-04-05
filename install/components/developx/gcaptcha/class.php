<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class DevelopxGcaptchaComponent extends \CBitrixComponent
{
    const MODULE_NAME = 'developx.gcaptcha';

    /**
     * @return string
     */
    private function getAction()
    {
        if (empty($_REQUEST["AJAX_CALL"]) && $_REQUEST["AJAX_CALL"] != 'Y'){
            return 'add';
        }else{
            return 'update';
        }
    }

    private function checkJQuery()
    {
        if ($this->arResult['OPTIONS']['INCLUDE_JQUERY'] == 'Y') {
            CJSCore::Init(["jquery"]);
        }
    }

    public function executeComponent()
    {
        if (Loader::includeModule(self::MODULE_NAME)) {
            $moduleObj = Developx\Gcaptcha\Options::getInstance();
            if ($moduleObj->checkCaptchaActive()) {
                $this->arResult['OPTIONS'] = $moduleObj->getOptions();
                $this->arResult['CAPTCHA_ACTION'] = $moduleObj->getCaptchaAction();
                $this->checkJQuery();
                $this->includeComponentTemplate($this->getAction());
            }
        }
    }
}