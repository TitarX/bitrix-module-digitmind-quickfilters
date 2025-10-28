<?php

use DigitMind\QuickFilters\Entities\QuickFiltersIblock;

$MESS['DIGITMIND_QUICKFILTERS_MODULE_UNINSTALLED_FAIL'] = 'The module has not been uninstalled';
$MESS['DIGITMIND_QUICKFILTERS_MODULE_BACK_TO_LIST'] = 'Back to the solutions list';

$MESS['DIGITMIND_QUICKFILTERS_MODULE_UNINSTALLED_SUCCESS'] = 'The module has been uninstalled';
$MESS['DIGITMIND_QUICKFILTERS_MODULE_UNINSTALLED_SUCCESS_DET'] = 'The "Quick Filters" module has been successfully uninstalled';
$MESS['DIGITMIND_QUICKFILTERS_MODULE_UNINSTALLED_SUCCESS_IBLOCK_LINK'] = 'If the module\'s iblock was not removed automatically during uninstallation, it can be deleted in the <a href="'
    . QuickFiltersIblock::getIblockTypeUrl()
    . '">iblock type section</a>';
