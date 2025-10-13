<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\IO\Directory;
use DigitMind\QuickFilters\Entities\QuickFiltersIblock;
use DigitMind\QuickFilters\Helpers\UrlRewriteHelper;

Loc::loadMessages(__FILE__);

class digitmind_quickfilters extends CModule
{
    var $MODULE_ID;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;

    function __construct()
    {
        include(__DIR__ . '/../include.php');

        $this->MODULE_ID = 'digitmind.quickfilters';
        $this->MODULE_NAME = Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = 'DigitMind';
        $this->PARTNER_URI = 'https://bxmaster.com';

        $arModuleVersion = [];
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

    /**
     * @throws Exception
     */
    function DoInstall(): void
    {
        global $APPLICATION;
        global $step;
        global $errors;

        $errors = '';
        $step = intval($step);

        if (!ModuleManager::isModuleInstalled('iblock')) {
            $errors = Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_NOT_INSTALLED_IBLOCK');

            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_INSTALL_DONE'),
                __DIR__ . '/step2.php'
            );
        } else {
            if ($step < 2 && !QuickFiltersIblock::isIblockExists()) {
                $APPLICATION->IncludeAdminFile(
                    Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_INSTALL'),
                    __DIR__ . '/step1.php'
                );
            } else {
                $documentRoot = Application::getDocumentRoot();

                // Создание инфоблока
                if (!empty($_POST['create_iblock']) && $_POST['create_iblock'] == 'Y') {
                    QuickFiltersIblock::createIblock();
                }

                // Создание правила в urlrewrite.php для резервного пути
                UrlRewriteHelper::createDmqFilter();

                $this->copyFiles($documentRoot);
                $this->createDirectories($documentRoot);

                $this->RegisterEvents();
                $this->InstallDB();

                ModuleManager::registerModule($this->MODULE_ID);

                $APPLICATION->IncludeAdminFile(
                    Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_INSTALL_DONE'),
                    __DIR__ . '/step2.php'
                );
            }
        }
    }

    function DoUninstall(): void
    {
        global $APPLICATION;
        global $step;
        global $errors;

        $errors = '';
        $step = intval($step);

        if ($step < 2 && QuickFiltersIblock::isIblockExists()) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_UNINSTALL'),
                __DIR__ . '/unstep1.php'
            );
        } else {
            // Удаление инфоблока
            if (!empty($_POST['delete_iblock']) && $_POST['delete_iblock'] == 'Y') {
                QuickFiltersIblock::deleteIblock();
                QuickFiltersIblock::deleteIblockType();
            }

            // Удаление правила в urlrewrite.php для резервного пути
            UrlRewriteHelper::deleteDmqFilter();

            $this->deleteFiles();
            $this->deleteDirectories();

            $this->UnRegisterEvents();
            $this->UnInstallDB();

            ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_UNINSTALL_DONE'),
                __DIR__ . '/unstep2.php'
            );
        }
    }

    // Определение места размещения модуля
    public function getPath($notDocumentRoot = false): string
    {
        if ($notDocumentRoot) {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }

    // Проверяем что система поддерживает D7
    public function isVersionD7(): bool
    {
        return (version_compare(ModuleManager::getVersion('main'), '14.00.00') >= 0);
    }

    function InstallDB(): bool
    {
        global $APPLICATION;
        global $DB;
        global $errors;

        $documentRoot = Application::getDocumentRoot();
        $errors = $DB->RunSQLBatch(
            "{$documentRoot}/bitrix/modules/digitmind.quickfilters/install/db/" . strtolower($DB->type) . '/install.sql'
        );
        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode('. ', $errors));
            return false;
        }

        return true;
    }

    function UnInstallDB(): bool
    {
        global $APPLICATION;
        global $DB;
        global $errors;

        $documentRoot = Application::getDocumentRoot();
        $errors = $DB->RunSQLBatch(
            "{$documentRoot}/bitrix/modules/digitmind.quickfilters/install/db/" . strtolower(
                $DB->type
            ) . '/uninstall.sql'
        );
        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode('. ', $errors));
            return false;
        }

        return true;
    }

    private function copyFiles($documentRoot): void
    {
        CopyDirFiles(__DIR__ . '/js', "{$documentRoot}/bitrix/js/{$this->MODULE_ID}", true, true, false);
        CopyDirFiles(__DIR__ . '/css', "{$documentRoot}/bitrix/css/{$this->MODULE_ID}", true, true, false);
        CopyDirFiles(__DIR__ . '/images', "{$documentRoot}/bitrix/images/{$this->MODULE_ID}", true, true, false);
    }

    private function deleteFiles(): void
    {
        DeleteDirFilesEx("/bitrix/js/{$this->MODULE_ID}");
        DeleteDirFilesEx("/bitrix/css/{$this->MODULE_ID}");
        DeleteDirFilesEx("/bitrix/images/{$this->MODULE_ID}");
    }

    private function createDirectories($documentRoot): void
    {
        $uploadDirectoryName = Option::get('main', 'upload_dir');

        $digitmindDirectoryPath = "{$documentRoot}/{$uploadDirectoryName}/{$this->MODULE_ID}";
        if (!Directory::isDirectoryExists($digitmindDirectoryPath)) {
            Directory::createDirectory($digitmindDirectoryPath);
        }
    }

    private function deleteDirectories(): void
    {
        $uploadDirectoryPath = Option::get('main', 'upload_dir');
        DeleteDirFilesEx("/{$uploadDirectoryPath}/{$this->MODULE_ID}");
    }

    function RegisterEvents(): void
    {
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            'DigitMind\QuickFilters\Events\PageEvents',
            'checkQuickFilter',
            1000
        );

        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'DigitMind\QuickFilters\Events\PageEvents',
            'setMeta',
            1000
        );
    }

    function UnRegisterEvents(): void
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            'DigitMind\QuickFilters\Events\PageEvents',
            'checkQuickFilter'
        );

        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'DigitMind\QuickFilters\Events\PageEvents',
            'setMeta'
        );
    }

    function GetModuleRightList(): array
    {
        return [
            "reference_id" => ['D'],
            "reference" => [
                '[D] ' . Loc::getMessage('DIGITMIND_QUICKFILTERS_RIGHT_DENIED')
            ]
        ];
    }
}
