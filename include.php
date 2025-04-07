<?php

use Bitrix\Main\Loader;

// При правильном именовании, классы подключаются автоматически
// Имена файлов классов должны быть в нижнем регистре
Loader::registerAutoloadClasses(
    'digitmind.quickfilters',
    [
        'DigitMind\QuickFilters\Events\PageEvents' => 'lib/events/PageEvents.php',
        'DigitMind\QuickFilters\Entities\OptionTable' => 'lib/entities/OptionTable.php',
        'DigitMind\QuickFilters\Helpers\MiscHelper' => 'lib/helpers/MiscHelper.php',
        'DigitMind\QuickFilters\Workers\Worker' => 'lib/workers/Worker.php'
    ]
);
