<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arAdminMenu = [
    'parent_menu' => 'global_menu_store',
    'sort' => 1000,
    'text' => Loc::getMessage('PERFCODE_BLANKD7_MENU_TEXT'),
    'title' => Loc::getMessage('PERFCODE_BLANKD7_MENU_TITLE'),
    'url' => '',
    'icon' => '',
    'page_icon' => '',
    'items_id' => 'perfcode_blankd7_menu',
    'items' => [
        [
            'text' => Loc::getMessage('PERFCODE_BLANKD7_SUBMENU_TEXT'),
            'title' => Loc::getMessage('PERFCODE_BLANKD7_SUBMENU_TITLE'),
            'url' => 'perfcode_blankd7_update.php?lang=' . LANGUAGE_ID,
            'icon' => ''
        ]
    ]
];

if (!empty($arAdminMenu)) {
    return $arAdminMenu;
} else {
    return false;
}
