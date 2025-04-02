<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use DigitMind\Sample\Helpers\MiscHelper;
use DigitMind\Sample\Entities\OptionTable;
use DigitMind\Sample\Workers\Worker;

define('OPT_RESULT_FILE_PATH', 'RESULT_FILE_PATH');

Loc::loadMessages(__FILE__);
Loader::includeModule('digitmind.sample');

@set_time_limit(360);

global $APPLICATION;
$APPLICATION->SetTitle(Loc::getMessage('DIGITMIND_SAMPLE_DOWORK_PAGE_TITLE'));

$mainCss = MiscHelper::getAssetsPath('css') . '/main.css';
Asset::getInstance()->addString('<link href="' . $mainCss . '" rel="stylesheet" type="text/css">');

CJSCore::Init(['jquery']);

Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/main.js');
Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/worker.js');

$request = Application::getInstance()->getContext()->getRequest();
$options = OptionTable::getData();
$filePath = $options[OPT_RESULT_FILE_PATH]['VALUE'] ?? '';

if ($request->isPost()) {
    $newFilePath = $request->getPost('selected_file_path');
    if (is_string($newFilePath)) {
        $newFilePath = trim($newFilePath);
        if (!empty($newFilePath)) {
            $arrParams = [
                'CODE' => OPT_RESULT_FILE_PATH,
                'VALUE' => $newFilePath
            ];

            $workResult = null;
            if (!empty($options[OPT_RESULT_FILE_PATH]['ID'])) {
                $workResult = OptionTable::update($options[OPT_RESULT_FILE_PATH]['ID'], $arrParams);
            } else {
                $workResult = OptionTable::add($arrParams);
            }

            // if (isset($workResult) && $workResult->isSuccess()) {
            //     //
            // } else {
            //     //
            // }
        }
    }

    $fullCurUrl = MiscHelper::getFullCurUrl();
    header("Location: $fullCurUrl");
    exit();
}

CAdminFileDialog::ShowScript(
    [
        'event' => 'OpenFileDialog',
        'arResultDest' => ['ELEMENT_ID' => 'selected_file_path'],
        'arPath' => [],
        'select' => 'F',
        'operation' => 'O',
        'showUploadTab' => true,
        'showAddToMenuTab' => false,
        'fileFilter' => 'xlsx',
        'allowAllFiles' => false,
        'saveConfig' => true
    ]
);
?>

<div class="wrapper">
    <?= Loc::getMessage('DIGITMIND_SAMPLE_DOWORK_PAGE_DESCRIPTION') ?>
</div>

<div class="wrapper">
    <form action="" method="post">
        <div>
            <input type="text" name="selected_file_path" id="selected_file_path" value="<?= $filePath ?>" size="64"
                   placeholder="<?= Loc::getMessage('DIGITMIND_SAMPLE_DOWORK_FILEPATH_PLACEHOLDER_TITLE') ?>"
                   readonly required>
            <button id='open_file_dialog_button' type="button"><?= Loc::getMessage(
                    'DIGITMIND_SAMPLE_DOWORK_FILEPATH_OPEN_TITLE'
                ) ?></button>
        </div>
        <div>
            <input type="submit" value="<?= Loc::getMessage('DIGITMIND_SAMPLE_DOWORK_FORM_SUBMIT') ?>">
        </div>
    </form>
</div>