<?php

use Bitrix\Main\Loader;

// При правильном именовании, классы подключаются автоматически
// Имена файлов классов должны быть в нижнем регистре
Loader::registerAutoloadClasses(
    'digitmind.multioptions',
    [
        'DigitMind\MultiOptions\Events\MailEvents' => 'lib/events/MailEvents.php',
        'DigitMind\MultiOptions\Entities\OptionTable' => 'lib/entities/OptionTable.php',
        'DigitMind\MultiOptions\Helpers\MiscHelper' => 'lib/helpers/MiscHelper.php'
    ]
);
