<?php

namespace DigitMind\QuickFilters\Entities;

use Exception;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\TypeTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use DigitMind\QuickFilters\Helpers\MiscHelper;

Loc::loadMessages(__FILE__);

class QuickFiltersIblock
{
    const IBLOCK_TYPE_ID = 'digit_mind_quick_filters';
    const IBLOCK_CODE = 'digit_mind_quick_filters';

    private static array $propMap = [
        '100' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_CONTENT_URL_NAME',
            'CODE' => 'CONTENT_URL',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'Y'
        ],
        '200' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_META_H1_NAME',
            'CODE' => 'META_H1',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ],
        '300' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_META_TITLE_NAME',
            'CODE' => 'META_TITLE',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ],
        '400' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_META_KEYWORDS_NAME',
            'CODE' => 'META_KEYWORDS',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ],
        '500' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_META_DESCRIPTION_NAME',
            'CODE' => 'META_DESCRIPTION',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ],
        '600' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_META_CANONICAL_NAME',
            'CODE' => 'META_CANONICAL',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ],
        '700' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_BC_NAME_NAME',
            'CODE' => 'BC_NAME',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ],
        '800' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_BC_AS_LINK_NAME',
            'CODE' => 'BC_AS_LINK',
            'PROPERTY_TYPE' => 'B',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ],
        '900' => [
            'NAME_LANG_CODE' => 'DIGITMIND_QUICKFILTERS_PROP_HTTP_CODE_NAME',
            'CODE' => 'HTTP_CODE',
            'PROPERTY_TYPE' => 'L',
            'MULTIPLE' => 'N',
            'IS_REQUIRED' => 'N'
        ]
    ];

    /**
     * @throws Exception
     */
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

        try {
            $result = IblockTable::add([
                'IBLOCK_TYPE_ID' => self::IBLOCK_TYPE_ID,
                'LID' => $siteIds,
                'CODE' => self::IBLOCK_CODE,
                'NAME' => $iblockName,
                'ACTIVE' => 'Y',
                'SORT' => 100,
                'DESCRIPTION_TYPE' => 'html',
            ]);

            if ($result->isSuccess()) {
                $returnResult = true;
                $iblockId = $result->getId();

                foreach (self::$propMap as $propIndex => $prop) {
                    $propAddResult = PropertyTable::add([
                        'IBLOCK_ID' => $iblockId,
                        'NAME' => Loc::getMessage($prop['NAME_LANG_CODE']),
                        'CODE' => $prop['CODE'],
                        'PROPERTY_TYPE' => $prop['PROPERTY_TYPE'],
                        'SORT' => $propIndex,
                        'MULTIPLE' => $prop['MULTIPLE'],
                        'IS_REQUIRED' => $prop['IS_REQUIRED']
                    ]);

                    if ($prop['PROPERTY_TYPE'] === 'L' && $propAddResult->isSuccess()) {
                        if ($prop['CODE'] === 'HTTP_CODE') {
                            foreach (MiscHelper::getHttpCodes() as $httpCodeIndex => $httpCode) {
                                PropertyEnumerationTable::add([
                                    'PROPERTY_ID' => $propAddResult->getId(),
                                    'VALUE' => $httpCode,
                                    'SORT' => $httpCodeIndex,
                                    'DEF' => ($httpCodeIndex == '200' ? 'Y' : 'N')
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $returnResult = $e->getMessage();
        }

        return $returnResult;
    }

    private static function createIblockType(): bool|string
    {
        $parameters = [
            'ID' => self::IBLOCK_TYPE_ID,
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 500,
            'LANG' => [
                'ru' => [
                    'NAME' => 'Быстрые фильтры',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы'
                ],
                'en' => [
                    'NAME' => 'Quick filters',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements'
                ],
            ],
        ];

        try {
            TypeTable::add($parameters);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private static function isIblockTypeExists(): bool|string
    {
        $returnResult = false;

        try {
            $result = TypeTable::getByPrimary(self::IBLOCK_TYPE_ID)->fetch();

            if (!empty($result)) {
                $returnResult = true;
            }
        } catch (Exception $e) {
            $returnResult = $e->getMessage();
        }

        return $returnResult;
    }

    private static function isIblockExists(): bool|string
    {
        $returnResult = false;

        try {
            $result = IblockTable::getList([
                'filter' => [
                    'CODE' => self::IBLOCK_CODE
                ],
                'select' => ['ID']
            ])->fetch();

            if (!empty($result)) {
                $returnResult = true;
            }
        } catch (Exception $e) {
            $returnResult = $e->getMessage();
        }

        return $returnResult;
    }
}
