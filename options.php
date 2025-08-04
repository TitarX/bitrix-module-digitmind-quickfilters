<?php

use Bitrix\Main\Localization\Loc;
use DigitMind\QuickFilters\Helpers\MiscHelper;

Loc::loadMessages(__FILE__);

global $APPLICATION;
?>

<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?lang=<?= LANGUAGE_ID ?>&mid=<?= MiscHelper::getModuleId() ?>">
    <?= bitrix_sessid_post() ?>

    <input type="checkbox" name="create_iblock" id="create_iblock" value="Y" checked>
    <label for="create_iblock"><?= Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_CREATE_IBLOCK') ?></label>
    <br><br>
    <input type="submit" name="sbutton" value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_INSTALL_NEXT') ?>">
</form>