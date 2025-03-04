<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;
use DigitMind\QuickFilters\Helpers\MiscHelper;
use DigitMind\QuickFilters\Entities\OptionTable;

define('OPT_MAIL_RULES_FOR_TASKS', 'MAIL_RULES_FOR_TASKS');

Loc::loadMessages(__FILE__);
Loader::includeModule('digitmind.quickfilters');

@set_time_limit(360);

global $APPLICATION;
$APPLICATION->SetTitle(Loc::getMessage('DIGITMIND_QUICKFILTERS_STATISTICS_PAGE_TITLE'));

$mainCss = MiscHelper::getAssetsPath('css') . '/main.css';
Asset::getInstance()->addString('<link href="' . $mainCss . '" rel="stylesheet" type="text/css">');

CJSCore::Init(['jquery']);

Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/main.js');
Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/statistics.js');

$request = Application::getInstance()->getContext()->getRequest();
?>

<div class="wrapper"></div>