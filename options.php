<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use DigitMind\QuickFilters\Entities\QuickFiltersIblock;
use DigitMind\QuickFilters\Helpers\MiscHelper;
use DigitMind\QuickFilters\Helpers\UrlRewriteHelper;

Loc::loadMessages(__FILE__);
$request = Application::getInstance()->getContext()->getRequest();

global $APPLICATION;

if ($request->isPost() && check_bitrix_sessid() && $request->getPost('recreate_iblock') !== null) {
    if (QuickFiltersIblock::isIblockExists()) {
        QuickFiltersIblock::deleteIblock();
    }

    $isIblockCreated = QuickFiltersIblock::createIblock();

    $iblockCreateResultParam = '&iblock_success=Y';
    if (!$isIblockCreated) {
        $iblockCreateResultParam = '&iblock_fail=Y';
    }

    $APPLICATION->RestartBuffer();
    print '<script>window.location.href = "' . $APPLICATION->GetCurPage()
        . '?lang=' . LANGUAGE_ID . '&mid=' . MiscHelper::getModuleId() . $iblockCreateResultParam . '"</script>';
    exit;
}

// Обработка создания/обновления правила UrlRewrite для dmqfilter
if ($request->isPost() && check_bitrix_sessid() && $request->getPost('recreate_dmqfilter') !== null) {
    UrlRewriteHelper::createDmqFilter();

    $ruleId = UrlRewriteHelper::getDmqFilterRuleFields()['ID'];
    $isRuleCreated = UrlRewriteHelper::isExists($ruleId);

    $ruleCreateResultParam = $isRuleCreated ? '&dmq_success=Y' : '&dmq_fail=Y';

    $APPLICATION->RestartBuffer();
    print '<script>window.location.href = "' . $APPLICATION->GetCurPage()
        . '?lang=' . LANGUAGE_ID . '&mid=' . MiscHelper::getModuleId() . $ruleCreateResultParam . '"</script>';
    exit;
}

// Уведомления пересоздания инфоблока
if ($request->getQuery('iblock_success') === 'Y') {
    CAdminMessage::ShowNote(Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_IBLOCK_RECREATED_SUCCESS'));
}
if ($request->getQuery('iblock_fail') === 'Y') {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_IBLOCK_RECREATED_FAIL'));
}

// Уведомления пересоздания правила UrlRewrite
if ($request->getQuery('dmq_success') === 'Y') {
    CAdminMessage::ShowNote(Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_DMQFILTER_RECREATED_SUCCESS'));
}
if ($request->getQuery('dmq_fail') === 'Y') {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_DMQFILTER_RECREATED_FAIL'));
}
?>

<!-- Описание свойств инфоблока -->
<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_FIELDS_DESCRIPTION') ?>

<!-- Кнопка пересоздания инфоблока -->
<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?lang=<?= LANGUAGE_ID ?>&mid=<?= MiscHelper::getModuleId() ?>"
      onsubmit="return confirm('<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_RECREATE_IBLOCK_CONFIRM') ?>?');">
    <?= bitrix_sessid_post() ?>
    <input type="submit" name="recreate_iblock"
           value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_RECREATE_IBLOCK_BUTTON') ?>">
</form>

<!-- Кнопка пересоздания правила dmqfilter -->
<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?lang=<?= LANGUAGE_ID ?>&mid=<?= MiscHelper::getModuleId() ?>"
      onsubmit="return confirm('<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_RECREATE_DMQFILTER_CONFIRM') ?>');">
    <?= bitrix_sessid_post() ?>
    <input type="submit" name="recreate_dmqfilter"
           value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_RECREATE_DMQFILTER_BUTTON') ?>">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const url = new URL(window.location);

        // Очистка параметров для пересоздания инфоблока
        url.searchParams.delete('iblock_success');
        url.searchParams.delete('iblock_fail');

        // Очистка параметров для пересоздания правила dmqfilter
        url.searchParams.delete('dmq_success');
        url.searchParams.delete('dmq_fail');

        window.history.replaceState({}, document.title, url.toString());
    });
</script>