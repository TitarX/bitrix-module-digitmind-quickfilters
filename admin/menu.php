<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arAdminMenu = [];

if (!empty($arAdminMenu)) {
    return $arAdminMenu;
} else {
    return false;
}
