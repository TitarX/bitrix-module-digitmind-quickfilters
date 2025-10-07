<?php

namespace DigitMind\QuickFilters\Entities;

use Bitrix\Main\LoaderException;
use CAdminMessage;
use CIBlock;
use CIBlockElement;
use CIBlockProperty;
use CIBlockType;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use DigitMind\QuickFilters\Helpers\MiscHelper;

try {
    Loader::includeModule('iblock');
} catch (LoaderException $e) {
    CAdminMessage::ShowMessage(
        Loc::getMessage('DIGITMIND_QUICKFILTERS_INCLUDE_MODULE_FAIL', ['#MODULE#' => 'iblock'])
    );
    exit;
}

try {
    Loader::includeModule('digitmind.quickfilters');
} catch (LoaderException $e) {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_INCLUDE_CURRENT_MODULE_FAIL'));
    exit;
}

Loc::loadMessages(__FILE__);

class QuickFiltersIblock
{
    private const IBLOCK_TYPE_ID = 'digitmind_quick_filters';
    private const IBLOCK_CODE = 'digitmind_quick_filters';
    private const LANGUAGE_DEFAULT = 'ru';

    /**
     * Свойиства инфоблока
     *
     * @return array<int, array<string, mixed>>
     */
    private static function getIblockPropertyValues(string|int $iblockId = 0): array
    {
        return [
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_PAGE_URL_NAME'),
                'SORT' => '100',
                'CODE' => 'PAGE_URL',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'Y',
                'HINT' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_PAGE_URL_NAME_HINT')
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_CONTENT_URL_NAME'),
                'SORT' => '100',
                'CODE' => 'CONTENT_URL',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'Y',
                'HINT' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_CONTENT_URL_NAME_HINT')
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
                'NAME' => Loc::getMessage('DIGITMIND_QUICKFILTERS_PROP_IS_BC_LINK_NAME'),
                'SORT' => '800',
                'CODE' => 'IS_BC_LINK',
                'PROPERTY_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'LIST_TYPE' => 'C',
                'VALUES' => [
                    [
                        'VALUE' => 'Y',
                        'DEF' => 'Y',
                        'SORT' => 100,
                        'XML_ID' => 'Y'
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

    /**
     * Возвращает коды свойств инфоблока
     *
     * @return array<int, string>
     */
    private static function getIblockPropertyCodesForFilter(): array
    {
        $result = [];

        foreach (self::getIblockPropertyValues() as $property) {
            $result[] = 'PROPERTY_' . $property['CODE'];
        }

        return $result;
    }

    /**
     * Возвращает URL административного раздела БУС для управления типом инфоблока
     *
     * @return string
     */
    public static function getIblockTypeUrl(): string
    {
        $lang = Application::getInstance()->getContext()->getRequest()->get('lang');
        if (empty($lang)) {
            $lang = !empty(LANGUAGE_ID) ? LANGUAGE_ID : self::LANGUAGE_DEFAULT;
        }

        $type = self::IBLOCK_TYPE_ID;

        return "/bitrix/admin/iblock_admin.php?type={$type}&lang={$lang}&admin=Y";
    }

    /**
     * Создание инофоблока
     *
     * @return bool|string
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

        $iblock = new CIBlock();

        $arFields = [
            'IBLOCK_TYPE_ID' => self::IBLOCK_TYPE_ID,
            'LID' => $siteIds,
            'CODE' => self::IBLOCK_CODE,
            'NAME' => $iblockName,
            'ACTIVE' => 'Y',
            'SORT' => 100,
            'DESCRIPTION_TYPE' => 'text',
            'INDEX_SECTION' => 'N',
            'INDEX_ELEMENT' => 'N',
            'WORKFLOW' => 'N' // WF_TYPE
        ];

        $iblockId = $iblock->Add($arFields);

        $returnResult = (!empty($iblockId) && is_numeric($iblockId));
        if ($returnResult) {
            foreach (self::getIblockPropertyValues($iblockId) as $iblockPropertyValues) {
                $iblockProperty = new CIBlockProperty();
                $iblockProperty->Add($iblockPropertyValues);
            }

            $arSettings = [
                'CODE' => [
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE' => 'Y',
                        'TRANSLITERATION' => 'Y'
                    ]
                ]
            ];

            CIBlock::setFields($iblockId, $arSettings);
        }

        return $returnResult;
    }

    /**
     * Создание типа инфоблока
     *
     * @return bool|string
     */
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
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы'
                ],
                'en' => [
                    'NAME' => 'Quick filters',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements'
                ]
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

    /**
     * Удаление типа инфоблока
     */
    public static function deleteIblockType(): void
    {
        $dbResult = CIBlockType::GetList(
            ['SORT' => 'ASC'],
            ['ID' => self::IBLOCK_TYPE_ID]
        );

        if ($dbResult->Fetch()) {
            CIBlockType::Delete(self::IBLOCK_TYPE_ID);
        }
    }

    /**
     * Удаление инфоблока
     */
    public static function deleteIblock(): void
    {
        $dbResult = CIBlock::GetList(
            ['SORT' => 'ASC'],
            [
                'TYPE' => self::IBLOCK_TYPE_ID,
                'CODE' => self::IBLOCK_CODE
            ]
        );

        if ($arResult = $dbResult->Fetch()) {
            CIBlock::Delete($arResult['ID']);
        }
    }

    /**
     * Проверка существования типа инфоблока
     *
     * @return bool
     */
    public static function isIblockTypeExists(): bool
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

    /**
     * Проверка существования инфоблока
     *
     * @return bool
     */
    public static function isIblockExists(): bool
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

    /**
     * Поиск элемента инфоблока, соответствующего переданному строковому значению
     *
     * @param string $pageUrl
     *
     * @return string|int
     */
    private static function findByPageUrl(string $pageUrl): string|int
    {
        $elementId = '';

        $dbResult = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            [
                'IBLOCK_TYPE' => self::IBLOCK_TYPE_ID,
                'IBLOCK_CODE' => self::IBLOCK_CODE,
                'PROPERTY_PAGE_URL' => '%' . trim($pageUrl, '/') . '%',
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            ['IBLOCK_ID', 'ID', 'PROPERTY_PAGE_URL']
        );

        while ($arElement = $dbResult->Fetch()) {
            if (!empty($arElement['PROPERTY_PAGE_URL_VALUE'])) {
                list($pageUrlFromProperty) = MiscHelper::nomalizeUrlPath($arElement['PROPERTY_PAGE_URL_VALUE']);
                if ($pageUrlFromProperty === $pageUrl) {
                    $elementId = $arElement['ID'];
                    break;
                }
            }
        }

        return $elementId;
    }

    /**
     * Получение списка элементов инфоблока по значению свойства PAGE_URL
     *
     * @param string $pageUrl
     *
     * @return array
     */
    public static function getByPageUrl(string $pageUrl): array
    {
        $result = [];

        $elementId = self::findByPageUrl($pageUrl);
        if (!empty($elementId)) {
            $arSelect = array_merge(
                ['ID', 'IBLOCK_ID'],
                self::getIblockPropertyCodesForFilter()
            );

            $dbResult = CIBlockElement::GetList(
                ['SORT' => 'ASC'],
                [
                    'IBLOCK_TYPE' => self::IBLOCK_TYPE_ID,
                    'IBLOCK_CODE' => self::IBLOCK_CODE,
                    'ID' => $elementId
                ],
                false,
                false,
                $arSelect
            );

            if ($arElement = $dbResult->Fetch()) {
                if (!empty($arElement['PAGE_URL_VALUE'])) {
                    $result['PAGE_URL'] = $arElement['PAGE_URL_VALUE'];
                }
                if (!empty($arElement['CONTENT_URL_VALUE'])) {
                    $result['CONTENT_URL'] = $arElement['CONTENT_URL_VALUE'];
                }
                if (!empty($arElement['META_H1_VALUE'])) {
                    $result['META_H1'] = $arElement['META_H1_VALUE'];
                }
                if (!empty($arElement['META_TITLE_VALUE'])) {
                    $result['META_TITLE'] = $arElement['META_TITLE_VALUE'];
                }
                if (!empty($arElement['META_KEYWORDS_VALUE'])) {
                    $result['META_KEYWORDS'] = $arElement['META_KEYWORDS_VALUE'];
                }
                if (!empty($arElement['META_DESCRIPTION_VALUE'])) {
                    $result['META_DESCRIPTION'] = $arElement['META_DESCRIPTION_VALUE'];
                }
                if (!empty($arElement['META_CANONICAL_VALUE'])) {
                    $result['META_CANONICAL'] = $arElement['META_CANONICAL_VALUE'];
                }
                if (!empty($arElement['HTTP_CODE_VALUE'])) {
                    $result['HTTP_CODE'] = $arElement['HTTP_CODE_VALUE'];
                }
                if (!empty($arElement['BC_NAME_VALUE'])) {
                    $result['BC_NAME'] = $arElement['BC_NAME_VALUE'];
                }
                if (!empty($arElement['IS_BC_LINK_VALUE'])) {
                    $result['IS_BC_LINK'] = $arElement['IS_BC_LINK_VALUE'];
                }
            }
        }

        return $result;
    }
}
