<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;
use DigitMind\MultiOptions\Helpers\MiscHelper;
use DigitMind\MultiOptions\Entities\OptionTable;

define('OPT_TECHAPPEAL_TOADDRESS', 'TECHAPPEAL_TOADDRESS');
define('OPT_TECHAPPEAL_SUBJECT', 'TECHAPPEAL_SUBJECT');

Loc::loadMessages(__FILE__);
Loader::includeModule('digitmind.multioptions');

@set_time_limit(360);

global $APPLICATION;
$APPLICATION->SetTitle(Loc::getMessage('DIGITMIND_MULTIOPTIONS_RULES_PAGE_TITLE'));

$mainCss = MiscHelper::getAssetsPath('css') . '/main.css';
Asset::getInstance()->addString('<link href="' . $mainCss . '" rel="stylesheet" type="text/css">');

CJSCore::Init(['jquery']);

Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/main.js');
Asset::getInstance()->addJs(MiscHelper::getAssetsPath('js') . '/rules.js');

$request = Application::getInstance()->getContext()->getRequest();
$options = OptionTable::getData();

if ($request->isPost()) {
    $action = $request->get('action');
    $action = trim($action);

    if ($action === 'saveparams') { // Сохранение параметров
        $APPLICATION->RestartBuffer();

        $phpInput = file_get_contents('php://input');
        $phpInput = json_decode($phpInput, true);

        $result['result'] = '';

        if (!empty($phpInput['techappealToaddress'])) {
            $arrParams = [
                'CODE' => OPT_TECHAPPEAL_TOADDRESS,
                'VALUE' => $phpInput['techappealToaddress']
            ];

            $queryResult = null;
            if (!empty($options[OPT_TECHAPPEAL_TOADDRESS]['ID'])) {
                $queryResult = OptionTable::update($options[OPT_TECHAPPEAL_TOADDRESS]['ID'], $arrParams);
            } else {
                $queryResult = OptionTable::add($arrParams);
            }
            $result = [];
            if (isset($queryResult) && $queryResult->isSuccess()) {
                $result['result'] = $queryResult->getId();
            } else {
                $result['result'] = 'fail';
            }
        }

        if ($result['result'] !== 'fail' && !empty($phpInput['techappealSubject'])) {
            $arrParams = [
                'CODE' => OPT_TECHAPPEAL_SUBJECT,
                'VALUE' => $phpInput['techappealSubject']
            ];

            $queryResult = null;
            if (!empty($options[OPT_TECHAPPEAL_SUBJECT]['ID'])) {
                $queryResult = OptionTable::update($options[OPT_TECHAPPEAL_SUBJECT]['ID'], $arrParams);
            } else {
                $queryResult = OptionTable::add($arrParams);
            }
            $result = [];
            if (isset($queryResult) && $queryResult->isSuccess()) {
                $result['result'] = $queryResult->getId();
            } else {
                $result['result'] = 'fail';
            }
        }

        print json_encode($result);

        exit();
    } elseif ($request->getPost('action') === 'message') { // Системное сообщение
        $APPLICATION->RestartBuffer();

        $messageType = $request->getPost('type');
        $messageText = $request->getPost('text');
        $messageArgs = $request->getPost('args');
        if (!is_array($messageArgs)) {
            $messageArgs = [];
        }

        $message = vsprintf(Loc::getMessage($messageText), $messageArgs);
        CAdminMessage::ShowMessage(['MESSAGE' => $message, 'TYPE' => $messageType]);

        exit();
    }
}

$options = OptionsTable::getData();

$techappealToaddressRules = [];
if (!empty($options[OPT_TECHAPPEAL_TOADDRESS]['VALUE'])) {
    $techappealToaddressRules = $options[OPT_TECHAPPEAL_TOADDRESS]['VALUE'];
}

$techappealSubjectRules = [];
if (!empty($options[OPT_TECHAPPEAL_SUBJECT]['VALUE'])) {
    $techappealSubjectRules = $options[OPT_TECHAPPEAL_SUBJECT]['VALUE'];
}
?>

<div class="wrapper">
    <fieldset>
        <legend><?= Loc::getMessage('DIGITMIND_MULTIOPTIONS_TECHAPPEAL_TOADDRESS') ?></legend>
        <div id="email-techappeal-toaddress-wrapper">
            <?php foreach ($techappealToaddressRules as $ruleValue): ?>
                <input type="text" class="email-techappeal-input techappeal_toaddress" value="<?= trim($ruleValue) ?>" size="64"
                       placeholder="<?= Loc::getMessage('DIGITMIND_MULTIOPTIONS_SUBSTR_OR_REGEX_PLACEHOLDER') ?>">
            <?php endforeach; ?>
            <input type="text" value="" class="email-techappeal-input techappeal_toaddress"
                   size="64" placeholder="<?= Loc::getMessage('DIGITMIND_MULTIOPTIONS_SUBSTR_OR_REGEX_PLACEHOLDER') ?>">
        </div>
    </fieldset>
</div>

<div class="wrapper">
    <fieldset>
        <legend><?= Loc::getMessage('DIGITMIND_MULTIOPTIONS_TECHAPPEAL_SUBJECT') ?></legend>
        <div id="email-techappeal-subject-wrapper">
            <?php foreach ($techappealSubjectRules as $ruleValue): ?>
                <input type="text" class="email-techappeal-input techappeal_subject" value="<?= trim($ruleValue) ?>" size="64"
                       placeholder="<?= Loc::getMessage('DIGITMIND_MULTIOPTIONS_SUBSTR_OR_REGEX_PLACEHOLDER') ?>">
            <?php endforeach; ?>
            <input type="text" value="" class="email-techappeal-input techappeal_subject"
                   size="64" placeholder="<?= Loc::getMessage('DIGITMIND_MULTIOPTIONS_SUBSTR_OR_REGEX_PLACEHOLDER') ?>">
        </div>
    </fieldset>
</div>

<input type="hidden" name="requested-page" id="requested-page" value="<?= $request->getRequestedPage() ?>">

<div class="wrapper">
    <div id="work-info-spinner"></div>
    <button id='save-params-button'><?= Loc::getMessage('DIGITMIND_MULTIOPTIONS_RULES_SAVE_BUTTON') ?></button>
</div>

<div class="wrapper">
    <div id="work-info"></div>
</div>