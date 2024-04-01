<?php

use Bitrix\Main\Loader;

// При правильном именовании, классы подключаются автоматически. Имена файлов классов должны быть в нижнем регистре.
Loader::registerAutoloadClasses(
    'digitmind.sample',
    [
        'DigitMind\Sample\Events\MainEvents' => 'lib/events/MainEvents.php',
        'DigitMind\Sample\Entities\OptionsTable' => 'lib/entities/OptionsTable.php',
        'DigitMind\Sample\Helpers\MiscHelper' => 'lib/helpers/MiscHelper.php',
        'DigitMind\Sample\Workers\Worker' => 'lib/workers/Worker.php'
    ]
);
