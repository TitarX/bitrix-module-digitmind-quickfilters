<?php

namespace DigitMind\QuickFilters\Entities;

use CIBlock;
use CIBlockProperty;
use CIBlockType;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use DigitMind\QuickFilters\Helpers\MiscHelper;

Loader::includeModule('iblock');
Loader::includeModule('digitmind.quickfilters');

Loc::loadMessages(__FILE__);

class QuickFiltersIblock
{
    private const IBLOCK_TYPE_ID = 'digitmind_quick_filters';
    private const IBLOCK_CODE = 'digitmind_quick_filters';
    private const LANGUAGE_DEFAULT = 'ru';

    private static function getIblockPropertyValues(string|int $iblockId): array
    {
        return [
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_CONTENT_URL_NAME'),
                'SORT' => '100',
                'CODE' => 'CONTENT_URL',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'Y'
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_META_H1_NAME'),
                'SORT' => '200',
                'CODE' => 'META_H1',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N'
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_META_TITLE_NAME'),
                'SORT' => '300',
                'CODE' => 'META_TITLE',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N'
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_META_KEYWORDS_NAME'),
                'SORT' => '400',
                'CODE' => 'META_KEYWORDS',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N'
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_META_DESCRIPTION_NAME'),
                'SORT' => '500',
                'CODE' => 'META_DESCRIPTION',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N'
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_META_CANONICAL_NAME'),
                'SORT' => '600',
                'CODE' => 'META_CANONICAL',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N'
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_BC_NAME_NAME'),
                'SORT' => '700',
                'CODE' => 'BC_NAME',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N'
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_BC_AS_LINK_NAME'),
                'SORT' => '800',
                'CODE' => 'BC_AS_LINK',
                'PROPERTY_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'LIST_TYPE' => 'C',
                'VALUES' => [
                    [
                        'VALUE' => '',
                        'DEF' => 'Y',
                        'SORT' => 100
                    ]
                ]
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_HTTP_CODE_NAME'),
                'SORT' => '900',
                'CODE' => 'HTTP_CODE',
                'PROPERTY_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'LIST_TYPE' => 'L',
                'VALUES' => MiscHelper::getHttpCodePropertyValues()
            ]
        ];
    }

    public static function getIblockTypeUrl(): string
    {
        $lang = Application::getInstance()->getContext()->getRequest()->get('lang');
        if (empty($lang)) {
            $lang = !empty(LANGUAGE_ID) ? LANGUAGE_ID : self::LANGUAGE_DEFAULT;
        }

        $type = self::IBLOCK_TYPE_ID;

        return "/bitrix/admin/iblock_admin.php?type={$type}&lang={$lang}&admin=Y";
    }

    public static function createIblock(): bool|string
    {
        $returnResult = false;

        $iblockName = Loc::getMessage('DIGITMIND_QUICKFILTERS_IBLOCK_NAME');

        if (self::isIblockExists() !== false) {
            return $returnResult;
        }

        if (self::isIblockTypeExists() === false) {
            $result = self::createIblockType();
            if ($result !== true) {
                return $returnResult;
            }
        }

        $siteIds = MiscHelper::getSiteIds();
        if (empty($siteIds)) {
            $siteIds[] = 's1';
        }

        $iblock = new CIBlock();

        $arFields = [
            'IBLOCK_TYPE_ID' => self::IBLOCK_TYPE_ID,
            'LID' => $siteIds,
            'CODE' => self::IBLOCK_CODE,
            'NAME' => $iblockName,
            'ACTIVE' => 'Y',
            'SORT' => 100,
            'DESCRIPTION_TYPE' => 'text',
        ];

        $iblockId = $iblock->Add($arFields);

        $returnResult = (!empty($iblockId) && is_numeric($iblockId));
        if ($returnResult) {
            foreach (self::getIblockPropertyValues($iblockId) as $iblockPropertyValues) {
                $iblockProperty = new CIBlockProperty();
                $iblockProperty->Add($iblockPropertyValues);
            }
        }

        return $returnResult;
    }

    private static function createIblockType(): bool|string
    {
        global $DB;

        $arFields = [
            'ID' => self::IBLOCK_TYPE_ID,
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => [
                'ru' => [
                    'NAME' => 'Быстрые фильтры',
                    'SECTION_NAME' => 'Разделы2',
                    'ELEMENT_NAME' => 'Элементы2',
                ],
                'en' => [
                    'NAME' => 'Quick filters',
                    'SECTION_NAME' => 'Sections2',
                    'ELEMENT_NAME' => 'Elements2',
                ],
            ]
        ];

        $iblocktype = new CIBlockType();
        $DB->StartTransaction();
        $addResult = $iblocktype->Add($arFields);
        if ($addResult) {
            $DB->Commit();
            return true;
        } else {
            $DB->Rollback();
            return $iblocktype->LAST_ERROR;
        }
    }

    private static function isIblockTypeExists(): bool
    {
        $returnResult = false;

        $dbResult = CIBlockType::GetList(
            ['SORT' => 'ASC'],
            ['ID' => self::IBLOCK_TYPE_ID]
        );

        if ($dbResult->Fetch()) {
            $returnResult = true;
        }

        return $returnResult;
    }

    private static function isIblockExists(): bool
    {
        $returnResult = false;

        $dbResult = CIBlock::GetList(
            ['SORT' => 'ASC'],
            [
                'TYPE' => self::IBLOCK_TYPE_ID,
                'CODE' => self::IBLOCK_CODE
            ]
        );

        if ($dbResult->Fetch()) {
            $returnResult = true;
        }

        return $returnResult;
    }
}
