<?php

namespace DigitMind\QuickFilters\Events;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use CAdminMessage;
use CHTTP;

try {
    Loader::includeModule('digitmind.quickfilters');
} catch (LoaderException $e) {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_INCLUDE_CURRENT_MODULE_FAIL'));
    exit;
}

class PageEvents
{
    private static string $pageUrl = '';
    private static string $contentUrl = '';
    private static string $metaH1 = '';
    private static string $metaTitle = '';
    private static string $metaKeywords = '';
    private static string $metaDescription = '';
    private static string $metaCanonical = '';
    private static string $httpCode = '';
    private static string $bc = '';
    private static bool $isBcLink = false;
    private static bool $isQuickFilter = false;

    public static function checkQuickFilter(): void
    {
        $currentUrl = self::prepareCurrentUrl($_SERVER['REQUEST_URI']);
        $isQuickFilter = self::fillData($currentUrl);

        if ($isQuickFilter) {
            CHTTP::SetStatus(self::$httpCode);
            readfile(self::$contentUrl);
            exit();
        }
    }

    public static function setMeta(): void
    {
        global $APPLICATION;

        if (self::$isQuickFilter) {
            if (!empty(self::$metaH1)) {
                $APPLICATION->SetTitle(self::$metaH1); // h1
            }

            if (!empty(self::$metaTitle)) {
                $APPLICATION->SetPageProperty('title', self::$metaTitle); // title
            }

            if (!empty(self::$metaKeywords)) {
                $APPLICATION->SetPageProperty('keywords', self::$metaKeywords); // keywords
            }

            if (!empty(self::$metaDescription)) {
                $APPLICATION->SetPageProperty('description', self::$metaDescription); // description
            }

            if (!empty(self::$metaCanonical)) {
                $APPLICATION->SetPageProperty('canonical', self::$metaCanonical); // canonical
            }

            if (!empty(self::$bc)) {
                $APPLICATION->AddChainItem(self::$bc, self::$isBcLink ? self::$pageUrl : ''); // breadcrumb
            }
        }
    }

    private static function prepareCurrentUrl(string $currentUrl): string
    {
        // file_put_contents(__DIR__ . '/try.txt', print_r($currentUrl, true), FILE_APPEND);
        // file_put_contents(__DIR__ . '/try.txt', PHP_EOL, FILE_APPEND);
        // file_put_contents(__DIR__ . '/try.txt', PHP_EOL, FILE_APPEND);
        // file_put_contents(__DIR__ . '/try.txt', '--------------------------------', FILE_APPEND);
        // file_put_contents(__DIR__ . '/try.txt', PHP_EOL, FILE_APPEND);
        // file_put_contents(__DIR__ . '/try.txt', PHP_EOL, FILE_APPEND);

        return '';
    }

    private static function fillData(string $currentUrl): bool
    {
        //

        return false;
    }

    private static function resetData(): void
    {
        self::$pageUrl = '';
        self::$contentUrl = '';
        self::$metaH1 = '';
        self::$metaTitle = '';
        self::$metaKeywords = '';
        self::$metaDescription = '';
        self::$metaCanonical = '';
        self::$httpCode = '';
        self::$bc = '';
        self::$isBcLink = false;
        self::$isQuickFilter = false;
    }
}
