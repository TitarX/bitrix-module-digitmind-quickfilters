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
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">

    <input type="checkbox" name="create_iblock" id="create_iblock" value="Y" checked>
    <label for="create_iblock"><?= Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_CREATE_IBLOCK') ?></label>
    <br><br>
    <input type="submit" name="sbutton" value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_INSTALL_NEXT') ?>">
</form>