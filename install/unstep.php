<?php

if (!check_bitrix_sessid()) {
    return;
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

print CAdminMessage::ShowNote(Loc::getMessage('NEWMODULE_MODULE_UNINSTALLED'));
