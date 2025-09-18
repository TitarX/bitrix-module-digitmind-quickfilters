<?php

use Bitrix\Main\Localization\Loc;
use DigitMind\QuickFilters\Helpers\MiscHelper;

Loc::loadMessages(__FILE__);

global $APPLICATION;
?>

<form method="post" action="<?= $APPLICATION->GetCurPage() ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="<?= MiscHelper::getModuleId() ?>">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">

    <input type="checkbox" name="delete_iblock" id="delete_iblock" value="Y">
    <label for="delete_iblock"><?= Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_DELETE_IBLOCK') ?></label>
    <br><br>
    <input type="submit" name="sbutton" value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_UNINSTALL_NEXT') ?>">
</form>