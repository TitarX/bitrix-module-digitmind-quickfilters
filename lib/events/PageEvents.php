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
}
