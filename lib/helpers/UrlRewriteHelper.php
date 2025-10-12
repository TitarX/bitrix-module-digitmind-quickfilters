<?php

namespace DigitMind\QuickFilters\Helpers;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UrlRewriter;
use CAdminMessage;

try {
    Loader::includeModule('digitmind.quickfilters');
} catch (LoaderException $e) {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_INCLUDE_CURRENT_MODULE_FAIL'));
    exit;
}

class UrlRewriteHelper
{
    public static function isExists(string $ruleId): bool
    {
        $getListResult = UrlRewriter::GetList(['ID' => 'bitrix:news']);
        if(!empty($getListResult))
        {
            //
        }
    }
}
