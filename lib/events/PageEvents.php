<?php

namespace DigitMind\QuickFilters\Events;

use Bitrix\Main\Loader;

Loader::includeModule('digitmind.quickfilters');

class PageEvents
{
    public static function checkQuickFilter()
    {
        // file_put_contents(__DIR__ . '/try.txt', print_r($_SERVER['REQUEST_URI'], true), FILE_APPEND);
        // file_put_contents(__DIR__ . '/try.txt', PHP_EOL, FILE_APPEND);

        if ($_SERVER['REQUEST_URI'] == '/catalog/t-shirts/') {
            readfile('https://bx.site/catalog/sportswear/');
            exit();
        }
    }
}
