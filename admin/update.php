<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;
use Perfcode\Blankd7\Helpers\MiscHelper;

Loc::loadMessages(__FILE__);
Loader::includeModule('perfcode.blankd7');

@set_time_limit(360);

global $APPLICATION;
$APPLICATION->SetTitle(Loc::getMessage('PERFCODE_BLANKD7_UPDATE_PAGE_TITLE'));

Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/perfcode_blankd7_main.js');
Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/perfcode_blankd7_update.js');

$request = Application::getInstance()->getContext()->getRequest();

CAdminFileDialog::ShowScript(
    array
    (
        'event' => 'OpenFileDialog',
        'arResultDest' => array('ELEMENT_ID' => 'selected_file_path'),
        'arPath' => array(),
        'select' => 'F',
        'operation' => 'O',
        'showUploadTab' => true,
        'showAddToMenuTab' => false,
        'fileFilter' => 'csv',
        'allowAllFiles' => false,
        'saveConfig' => true
    )
);
?>

<fieldset>
    <legend><?= Loc::getMessage('PERFCODE_BLANKD7_UPDATE_FILE_FIELDSET_LEGEND') ?></legend>
    <input type="text" name="selected_file_path" id="selected_file_path" size="64" placeholder="<?= Loc::getMessage('PERFCODE_BLANKD7_UPDATE_FILEPATH_PLACEHOLDER_TITLE') ?>"
           readonly>
    <button id='open_file_dialog_button'>Открыть</button>
</fieldset>

<br>

<button id="start-update-button" onclick="prepareUpdate('<?= $request->getRequestUri() ?>');"><?= Loc::getMessage('PERFCODE_BLANKD7_UPDATE_FILE_STAT_BUTTON') ?></button>
