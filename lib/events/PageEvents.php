<?php

namespace DigitMind\QuickFilters\Events;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use CAdminMessage;
use CHTTP;
use DigitMind\QuickFilters\Entities\QuickFiltersIblock;
use DigitMind\QuickFilters\Helpers\MiscHelper;

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

    /**
     * Обработчик события OnPageStart, начала исполняемого раздела пролога сайта
     *
     * Подстановка содержимого страницы быстрого фильтра, если текущий URL соответствует
     * и задание кода HTTP-ответа
     *
     * @return void
     */
    public static function checkQuickFilter(): void
    {
        list($currentUrlPath, $currentUrlQuery) = MiscHelper::nomalizeUrlPath($_SERVER['REQUEST_URI']);
        $isQuickFilter = self::fillData($currentUrlPath, $currentUrlQuery);

        if ($isQuickFilter) {
            CHTTP::SetStatus(self::$httpCode);
            readfile(self::$contentUrl);
            exit();
        }
    }

    /**
     * Обработчик события OnEpilog, завершения обработки визуальной части эпилога сайта
     *
     * Установка мета-тегов и хлебных крошек
     *
     * @return void
     */
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

    /**
     * Поиск элемента инфоблока, соответствующего текущему URL, и заполнение полей при нахождении
     *
     * @param string $currentUrlPath
     * @param string $currentUrlQuery
     *
     * @return bool
     */
    private static function fillData(string $currentUrlPath, string $currentUrlQuery): bool
    {
        $matchDatas = QuickFiltersIblock::getListByPageUrlContains($currentUrlPath);
        if (!empty($matchDatas)) {
            //
        }

        return false;
    }

    /**
     * Сброс заполненных полей
     *
     * @return void
     */
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
