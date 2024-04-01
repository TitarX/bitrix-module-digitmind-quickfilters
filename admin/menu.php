<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arAdminMenu = [
    'parent_menu' => 'global_menu_services',
    'sort' => 1000,
    'text' => Loc::getMessage('DIGITMIND_SAMPLE_MENU_TEXT'),
    'title' => Loc::getMessage('DIGITMIND_SAMPLE_MENU_TITLE'),
    'url' => '',
    'icon' => '',
    'page_icon' => '',
    'items_id' => 'digitmind_sample_menu',
    'items' => [
        [
            'text' => Loc::getMessage('DIGITMIND_SAMPLE_MENU_WORKER_TEXT'),
            'title' => Loc::getMessage('DIGITMIND_SAMPLE_MENU_WORKER_TITLE'),
            'url' => 'digitmind_sample_worker.php?lang=' . LANGUAGE_ID,
            'icon' => ''
        ]
    ]
];

if (!empty($arAdminMenu)) {
    return $arAdminMenu;
} else {
    return false;
}
