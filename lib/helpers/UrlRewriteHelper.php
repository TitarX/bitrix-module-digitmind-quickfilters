<?php

namespace DigitMind\QuickFilters\Helpers;

use Bitrix\Main\ArgumentNullException;
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
    /**
     * Возвращает массив полей правила для резервного пути
     *
     * @return array Массив полей правила
     *               ID - идентификатор правила
     *               CONDITION - условие срабатывания правила (регулярное выражение)
     *               RULE - правило преобразования
     *               PATH - путь к файлу, который будет обрабатывать запрос
     *               SORT - сортировка правила
     */
    public static function getDmqFilterRuleFields(): array
    {
        return [
            'ID' => 'digitmind:dmqfilter',
            'CONDITION' => '#^/dmqfilter/#',
            'RULE' => '',
            'PATH' => '/index.php',
            'SORT' => 0
        ];
    }

    /**
     * Проверяет наличие правила в таблице URLRewrite по его ID
     *
     * @param string $ruleId ID правила
     *
     * @return bool true - правило с таким ID существует, false - не существует
     */
    public static function isExists(string $ruleId): bool
    {
        $siteId = SITE_ID;
        $filter = ['ID' => $ruleId];

        try {
            $getListResult = UrlRewriter::GetList($siteId, $filter);
        } catch (ArgumentNullException $e) {
            CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_URLREWRITE_IS_EXISTS_FAIL'));
        }

        if (!empty($getListResult)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Сохраняет правило в таблице URLRewrite
     *
     * @param array $fields Массив полей правила
     *                      ID - идентификатор правила (обязательное поле)
     *                      CONDITION - условие срабатывания правила (регулярное выражение)
     *                      RULE - правило преобразования
     *                      PATH - путь к файлу, который будет обрабатывать запрос
     *                      SORT - сортировка правила
     *
     * @return bool true - сохранение прошло успешно, false - ошибка при сохранении
     */
    public static function save(array $fields): bool
    {
        $returnResult = false;

        if (empty($fields['ID'])) {
            CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_URLREWRITE_SAVE_ID_FAIL'));
        }

        $siteId = SITE_ID;
        $filter = ['ID' => $fields['ID']];

        try {
            if (self::isExists($fields['ID'])) {
                UrlRewriter::update($siteId, $filter, $fields);
            } else {
                UrlRewriter::add($siteId, $fields);
            }

            $returnResult = true;
        } catch (ArgumentNullException $e) {
            CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_URLREWRITE_SAVE_FAIL'));
        }

        return $returnResult;
    }

    /**
     * Удаляет правило из таблицы URLRewrite по его ID
     *
     * @param string $ruleId ID правила
     *
     * @return bool true - удаление прошло успешно, false - ошибка при удалении
     */
    public static function delete(string $ruleId): bool
    {
        $returnResult = false;

        $siteId = SITE_ID;
        $filter = ['ID' => $ruleId];

        try {
            UrlRewriter::delete($siteId, $filter);

            $returnResult = true;
        } catch (ArgumentNullException $e) {
            CAdminMessage::ShowMessage(Loc::getMessage('DIGITMIND_QUICKFILTERS_URLREWRITE_DELETE_FAIL'));
        }

        return $returnResult;
    }

    /**
     * Создаёт или обновляет правило для резервного пути
     *
     * @return void
     */
    public static function createDmqFilter(): void
    {
        $ruleFields = self::getDmqFilterRuleFields();
        self::save($ruleFields);
    }

    /**
     * Удаляет правило для резервного пути
     *
     * @return void
     */
    public static function deleteDmqFilter(): void
    {
        $ruleFields = self::getDmqFilterRuleFields();
        self::delete($ruleFields['ID']);
    }
}
