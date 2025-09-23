<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use DigitMind\QuickFilters\Entities\QuickFiltersIblock;
use DigitMind\QuickFilters\Helpers\MiscHelper;

Loc::loadMessages(__FILE__);
$request = Application::getInstance()->getContext()->getRequest();

global $APPLICATION;

if ($request->isPost() && check_bitrix_sessid() && $request->getPost('recreate_iblock') !== null) {
    if (QuickFiltersIblock::isIblockExists()) {
        QuickFiltersIblock::deleteIblock();
    }

    $isIblockCreated = QuickFiltersIblock::createIblock();

    $iblockCreateResultParam = '&success=Y';
    if (!$isIblockCreated) {
        $iblockCreateResultParam = '&fail=Y';
    }

    $APPLICATION->RestartBuffer();
    print '<script>window.location.href = "' . $APPLICATION->GetCurPage()
        . '?lang=' . LANGUAGE_ID . '&mid=' . MiscHelper::getModuleId() . $iblockCreateResultParam . '"</script>';
    exit;
}

if ($request->getQuery('success') === 'Y') {
    CAdminMessage::ShowNote(Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_IBLOCK_RECREATED_SUCCESS'));
}
if ($request->getQuery('fail') === 'Y') {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_IBLOCK_RECREATED_FAIL'));
}
?>

<!-- Описание свойств инфоблока -->
<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_FIELDS_DESCRIPTION') ?>

<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?lang=<?= LANGUAGE_ID ?>&mid=<?= MiscHelper::getModuleId() ?>"
      onsubmit="return confirm('<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_RECREATE_IBLOCK_CONFIRM') ?>?');">
    <?= bitrix_sessid_post() ?>
    <input type="submit" name="recreate_iblock"
           value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_RECREATE_IBLOCK_BUTTON') ?>">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const url = new URL(window.location);
        url.searchParams.delete('success');
        url.searchParams.delete('fail');
        window.history.replaceState({}, document.title, url.toString());
    });
</script>