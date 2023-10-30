<?php

namespace Perfcode\Blankd7\Entities;

use Bitrix\Main\Entity;
use Bitrix\Main\SystemException;

class ParamsTable extends Entity\DataManager
{
    /**
     * Метод возвращает имя таблицы
     *
     * @access public
     * @static
     *
     * @return string Имя таблицы
     */
    public static function getTableName()
    {
        return 'perfcode_blankd7_params';
    }

    /**
     * Метод возвращает карту полей таблицы базы данных
     *
     * @access public
     * @static
     *
     * @return array Массив объектов, описывающих поля таблицы в базе данных
     * @throws SystemException
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new Entity\TextField(
                'VALUE',
                [
                    'default_value' => '',
                ]
            )
        ];
    }
}
