<?php

if (!check_bitrix_sessid()) {
    return;
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

global $APPLICATION;

if (empty($errors)) {
    CAdminMessage::ShowMessage(
        [
            'TYPE' => 'OK',
            'MESSAGE' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_UNINSTALLED_SUCCESS'),
            'DETAILS' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_UNINSTALLED_SUCCESS_DET'),
            'HTML' => true
        ]
    );
} else {
    CAdminMessage::ShowMessage(
        [
            'TYPE' => 'ERROR',
            'MESSAGE' => Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_UNINSTALLED_FAIL'),
            'DETAILS' => $errors,
            'HTML' => true
        ]
    );
}
?>

<form method="post" action="<?= $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="submit" name="sbutton" value="<?= Loc::getMessage('DIGITMIND_QUICKFILTERS_MODULE_BACK_TO_LIST') ?>">
</form>
