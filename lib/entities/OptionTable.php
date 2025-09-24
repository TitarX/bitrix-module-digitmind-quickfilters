<?php

namespace DigitMind\QuickFilters\Entities;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\Type;
use Bitrix\Main\Entity;
use Bitrix\Main\SystemException;
use CAdminMessage;

try {
    Loader::includeModule('digitmind.quickfilters');
} catch (LoaderException $e) {
    CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_INCLUDE_CURRENT_MODULE_FAIL'));
    exit;
}

class OptionTable extends Entity\DataManager
{
    /**
     * Возвращает имя таблицы
     *
     * @access public
     * @static
     *
     * @return string Имя таблицы
     */
    public static function getTableName(): string
    {
        return 'digitmind_quickfilters_option';
    }

    /**
     * Проверяет существование таблицы
     *
     * @return bool
     */
    public static function isExist(): bool
    {
        return Application::getConnection()->isTableExists(self::getTableName());
    }

    /**
     * Создаёт таблицу базы данных
     *
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function createTable(): void
    {
        Entity\Base::getInstance(self::class)->createDbTable();
    }

    /**
     * Возвращает карту полей таблицы базы данных
     *
     * @access public
     * @static
     *
     * @return array Массив объектов, описывающих поля таблицы в базе данных
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            new Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new Entity\StringField(
                'CODE',
                [
                    'required' => true
                ]
            ),
            new Entity\TextField(
                'VALUE',
                [
                    'serialized' => true,
                    'default_value' => ''
                ]
            ),
            new Entity\DatetimeField(
                'CREATE_DATE',
                [
                    'default_value' => new Type\DateTime(),
                ]
            ),
            new Entity\DatetimeField(
                'UPDATE_DATE',
                [
                    'default_value' => new Type\DateTime(),
                ]
            ),
        ];
    }

    /**
     * @return array
     *
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    public static function getAllData(): array
    {
        $result = [];

        $dbResult = OptionTable::getList([
            'select' => ['*']
        ]);
        while ($arrResult = $dbResult->fetch()) {
            $result[$arrResult['CODE']] = [
                'ID' => $arrResult['ID'],
                'VALUE' => $arrResult['VALUE'],
                'CREATE_DATE' => $arrResult['CREATE_DATE'],
                'UPDATE_DATE' => $arrResult['UPDATE_DATE']
            ];
        }

        return $result;
    }

    /**
     * @param string $code
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getOption(string $code): array
    {
        return OptionTable::getRow(['CODE' => $code]) ?: [];
    }

    /**
     * @param string $code
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function isOption(string $code): bool
    {
        $result = false;

        $dbResult = OptionTable::getList([
            'filter' => ['CODE' => $code],
            'select' => ['ID']
        ]);
        if ($dbResult->fetch()) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param Entity\Event $event
     * @return Entity\EventResult
     */
    public static function onBeforeAdd(Entity\Event $event): Entity\EventResult
    {
        $result = new Entity\EventResult;
        $data = $event->getParameter('fields');
        $modFields = [];

        if (!isset($data['CREATE_DATE'])) {
            $modFields['CREATE_DATE'] = new Type\DateTime();
        }

        if (!empty($modFields)) {
            $result->modifyFields($modFields);
        }
        return $result;
    }

    /**
     * @param Entity\Event $event
     * @return Entity\EventResult
     */
    public static function onBeforeUpdate(Entity\Event $event): Entity\EventResult
    {
        $result = new Entity\EventResult;
        $data = $event->getParameter('fields');
        $modFields = [];

        if (!isset($data['UPDATE_DATE'])) {
            $modFields['UPDATE_DATE'] = new Type\DateTime();
        }

        if (!empty($modFields)) {
            $result->modifyFields($modFields);
        }
        return $result;
    }
}
