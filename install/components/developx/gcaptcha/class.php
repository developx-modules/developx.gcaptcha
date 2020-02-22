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
            return 'ADD';
        }else{
            return 'RESET';
        }
    }

    public function executeComponent()
    {
        if (Loader::includeModule(self::MODULE_NAME)) {
            $moduleObj = Developx\Gcaptcha\Options::getInstance();
            if ($moduleObj->checkCaptchaActive()) {
                $this->arResult['OPTIONS'] = $moduleObj->getOptions();
                $this->arResult['CAPTCHA_ACTION'] = $moduleObj->getCaptchaAction();
                $this->arResult['ACTION'] = $this->getAction();
                $this->includeComponentTemplate();
            }
        }
    }
}