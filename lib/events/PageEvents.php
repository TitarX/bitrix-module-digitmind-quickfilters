<?php

namespace DigitMind\QuickFilters\Events;

use Bitrix\Main\Application;
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
    private static string $dmqfIblockId = 'dmqf_iblock_id';

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
        $fullCurUrl = MiscHelper::getFullCurUrl();
        list($currentUrlPath) = MiscHelper::nomalizeUrlPath($fullCurUrl, true);

        $currentUrlPathTrimmed = trim($currentUrlPath, '/');
        if (!empty($currentUrlPathTrimmed)) {
            $matchDatas = QuickFiltersIblock::getByPageUrl($currentUrlPath);
            if (!empty($matchDatas['ID']) && !empty($matchDatas['CONTENT_URL'])) {
                $contentUrl = MiscHelper::nomalizeFullCurUrl($matchDatas['CONTENT_URL']);
                if (MiscHelper::checkUrl200($contentUrl) === true) {
                    $httpCode = $matchDatas['HTTP_CODE'] ?? '200';
                    $contentUrl = MiscHelper::addGetParam($contentUrl, self::$dmqfIblockId, $matchDatas['ID']);

                    CHTTP::SetStatus($httpCode);
                    readfile($contentUrl);
                    exit();
                }
            }
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

        $dmqfIblockId = Application::getInstance()->getContext()->getRequest()->get('dmqf_iblock_id');
        if (!empty($dmqfIblockId) && is_numeric($dmqfIblockId)) {
            $elementProperties = QuickFiltersIblock::getElementById($dmqfIblockId);
            if (!empty($elementProperties)) {
                if (!empty($elementProperties['META_TITLE'])) {
                    $APPLICATION->SetPageProperty('title', $elementProperties['META_TITLE']); // title
                }

                if (!empty($elementProperties['META_KEYWORDS'])) {
                    $APPLICATION->SetPageProperty('keywords', $elementProperties['META_KEYWORDS']); // keywords
                }

                if (!empty($elementProperties['META_DESCRIPTION'])) {
                    $APPLICATION->SetPageProperty('description', $elementProperties['META_DESCRIPTION']); // description
                }

                if (!empty($elementProperties['META_CANONICAL'])) {
                    $APPLICATION->SetPageProperty('canonical', $elementProperties['META_CANONICAL']); // canonical
                }

                if (!empty($elementProperties['META_H1'])) {
                    $APPLICATION->SetTitle($elementProperties['META_H1']); // h1
                }

                if (!empty($elementProperties['BC_NAME'])) {
                    $APPLICATION->AddChainItem($elementProperties['BC_NAME'], $elementProperties['PAGE_URL']); // breadcrumb
                }
            }
        }
    }
}
