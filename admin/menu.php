<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arAdminMenu = array(
//    'parent_menu' => 'global_menu_services',
//    'sort' => 1000,
//    'text' => Loc::getMessage(''),
//    'title' => Loc::getMessage(''),
//    'url' => '',
//    'icon' => '',
//    'page_icon' => '',
//    'items_id' => 'perfcode_blankd7_menu',
//    'items' => array(
//        array(
//            'text' => Loc::getMessage(''),
//            'title' => Loc::getMessage(''),
//            'url' => 'perfcode_blankd7_action.php?lang=' . LANGUAGE_ID,
//            'icon' => ''
//        )
//    )
);

if (!empty($arAdminMenu)) {
    return $arAdminMenu;
} else {
    return false;
}
