<?php

namespace DigitMind\QuickFilters\Events;

use Bitrix\Main\Loader;
use CHTTP;

Loader::includeModule('digitmind.quickfilters');

class PageEvents
{
    public static function checkQuickFilter()
    {
        // Вывод результата фильтра каталога по URL несуществующего (произвольного) раздела каталога
        if ($_SERVER['REQUEST_URI'] == '/catalog/dresses2/') {
            $newContentUrl = 'https://bx.site/catalog/sportswear/filter/price-base-from-2428-to-2636/color_ref-is-white/sizes_clothes-is-a11f96c3b88d222460d9796067d28b0c/apply/';

            CHTTP::SetStatus('200 OK');
            readfile($newContentUrl);
            exit();
        }
    }

    public static function setMeta()
    {
        global $APPLICATION;

        if ($_SERVER['REQUEST_URI'] == '/catalog/sportswear/filter/price-base-from-2428-to-2636/color_ref-is-white/sizes_clothes-is-a11f96c3b88d222460d9796067d28b0c/apply/') {
            $APPLICATION->SetTitle('Проверка h1'); // h1
            $APPLICATION->SetPageProperty('title', 'Проверка title'); // title
            $APPLICATION->SetPageProperty('description', 'Проверка description'); // description
            $APPLICATION->SetPageProperty('keywords', 'Проверка keywords'); // keywords
            $APPLICATION->SetPageProperty('canonical', 'Проверка canonical'); // canonical

            $APPLICATION->AddChainItem('Проверка breadcrumb', '/catalog/pants/'); // breadcrumb
        }
    }
}
