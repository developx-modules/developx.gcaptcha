<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

if (class_exists('developx_gcaptcha')) {
    return;
}

class developx_gcaptcha extends CModule
{
    /** @var string */
    public $MODULE_ID;

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];

        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        Loc::loadMessages(__FILE__);
        $this->MODULE_ID = 'developx.gcaptcha';
        $this->MODULE_NAME = Loc::getMessage('DX_CPT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DX_CPT_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = 'Developx';
        $this->PARTNER_URI = 'https://developx.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallFiles();
    }

    public function doUninstall()
    {
        ModuleManager::unregisterModule($this->MODULE_ID);
        $this->uninstallFiles();
    }

    public function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        return true;
    }

    public function uninstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/developx/comments");
    }
}
