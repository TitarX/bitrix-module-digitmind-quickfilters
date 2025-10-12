<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;

try {
    // При правильном именовании, классы подключаются автоматически
    // Имена файлов классов должны быть в нижнем регистре
    Loader::registerAutoloadClasses(
        'digitmind.quickfilters',
        [
            'DigitMind\QuickFilters\Events\MainEvents' => 'lib/events/MainEvents.php',
            'DigitMind\QuickFilters\Events\PageEvents' => 'lib/events/PageEvents.php',
            'DigitMind\QuickFilters\Entities\OptionTable' => 'lib/entities/OptionTable.php',
            'DigitMind\QuickFilters\Entities\QuickFiltersIblock' => 'lib/entities/QuickFiltersIblock.php',
            'DigitMind\QuickFilters\Helpers\MiscHelper' => 'lib/helpers/MiscHelper.php',
            'DigitMind\QuickFilters\Helpers\UrlRewriteHelper' => 'lib/helpers/UrlRewriteHelper.php'
        ]
    );
} catch (LoaderException $e) {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_REGISTER_CLASSES_FAIL'));
    exit;
}
