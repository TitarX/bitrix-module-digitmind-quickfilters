<?php

use Bitrix\Main\Loader;

// При правильном именовании, классы подключаются автоматически. Имена файлов классов должны быть в нижнем регистре.
Loader::registerAutoloadClasses(
    'perfcode.blankd7',
    [
        'Perfcode\Blankd7\Events\MainEvents' => 'lib/events/MainEvents.php',
        'Perfcode\Blankd7\Helpers\MiscHelper' => 'lib/helpers/MiscHelper.php'
    ]
);
