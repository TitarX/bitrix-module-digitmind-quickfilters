<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin.php");

use Bitrix\Main\Loader;

$includeFilePath = Loader::getLocal('modules/digitmind.quickfilters/admin/worker.php');
if ($includeFilePath !== false) {
    require_once $includeFilePath;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
