<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin.php");

use Bitrix\Main\Loader;

$includeFilePath = Loader::getLocal('modules/digitmind.multioptions/admin/statistics.php');
if ($includeFilePath !== false) {
    require_once $includeFilePath;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
