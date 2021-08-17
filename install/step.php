<?php

if (!check_bitrix_sessid()) {
    return;
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

print CAdminMessage::ShowNote(Loc::getMessage('PERFCODE_BLANKD7_MODULE_INSTALLED'));
