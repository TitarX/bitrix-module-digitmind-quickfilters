<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arAdminMenu = [
    'parent_menu' => 'global_menu_services',
    'sort' => 1000,
    'text' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MENU_TEXT'),
    'title' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MENU_TITLE'),
    'url' => '',
    'icon' => '',
    'page_icon' => '',
    'items_id' => 'digitmind_quickfilters_menu',
    'items' => [
        [
            'text' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MENU_RULES_TEXT'),
            'title' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MENU_RULES_TITLE'),
            'url' => "digitmind_quickfilters_rules.php?lang=" . LANGUAGE_ID,
            'icon' => ''
        ],
        [
            'text' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MENU_STATISTICS_TEXT'),
            'title' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MENU_STATISTICS_TITLE'),
            'url' => "digitmind_quickfilters_statistics.php?lang=" . LANGUAGE_ID,
            'icon' => ''
        ]
    ]
];

if (!empty($arAdminMenu)) {
    return $arAdminMenu;
} else {
    return false;
}
