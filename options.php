<?php

use Bitrix\Main\Localization\Loc;
use DigitMind\QuickFilters\Helpers\MiscHelper;

Loc::loadMessages(__FILE__);

global $APPLICATION;
?>

<!-- Описание свойств инфоблока -->
<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_FIELDS_DESCRIPTION') ?>

<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?lang=<?= LANGUAGE_ID ?>&mid=<?= MiscHelper::getModuleId() ?>">
    <?= bitrix_sessid_post() ?>
    <input type="submit" name="recreate_iblock"
           value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_RECREATE_IBLOCK_BUTTON') ?>">
</form>