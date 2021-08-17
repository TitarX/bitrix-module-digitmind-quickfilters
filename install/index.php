<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class perfcode_blankd7 extends CModule
{
    var $MODULE_ID = 'perfcode.blankd7';
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;

    function perfcode_blankd7()
    {
        $this->MODULE_NAME = Loc::getMessage('PERFCODE_BLANKD7_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('PERFCODE_BLANKD7_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = '';

        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        if (is_array($arModuleVersion)) {
            if (array_key_exists('VERSION', $arModuleVersion)) {
                $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            }

            if (array_key_exists('VERSION_DATE', $arModuleVersion)) {
                $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            }
        }
    }

    function DoInstall()
    {
        global $APPLICATION;

        // Действия при установке модуля

        $this->registerEvents();
        $this->InstallDB();

        RegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(Loc::getMessage('PERFCODE_BLANKD7_MODULE_INSTALL'), __DIR__ . '/step.php');
    }

    function DoUninstall()
    {
        global $APPLICATION;

        // Действия при удалении модуля

        $this->unRegisterEvents();
        $this->UnInstallDB();

        UnRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(Loc::getMessage('PERFCODE_BLANKD7_MODULE_UNINSTALL'), __DIR__ . '/unstep.php');
    }

    function InstallDB()
    {
        return true;
    }

    function UnInstallDB()
    {
        return true;
    }

    private function registerEvents()
    {
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'Perfcode\Blankd7\Events\MainEvents',
            'EpilogHandler',
            1000
        );
    }

    private function unRegisterEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'Perfcode\Blankd7\Events\MainEvents',
            'EpilogHandler'
        );
    }
}
