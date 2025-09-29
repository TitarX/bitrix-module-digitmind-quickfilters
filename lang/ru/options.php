<?php

$MESS['DIGITMIND_QUICKFILTERS_RECREATE_IBLOCK_BUTTON'] = 'Пересоздать инфоблок модуля';
$MESS['DIGITMIND_QUICKFILTERS_RECREATE_IBLOCK_CONFIRM'] = 'Подтверждаете пересоздание инфоблока модуля';

$MESS['DIGITMIND_QUICKFILTERS_MODULE_IBLOCK_RECREATED_SUCCESS'] = 'Инфоблок модуля успешно пересоздан';
$MESS['DIGITMIND_QUICKFILTERS_MODULE_IBLOCK_RECREATED_FAIL'] = 'Не удалось пересоздать инфоблок модуля';

$MESS['DIGITMIND_QUICKFILTERS_FIELDS_DESCRIPTION'] = "<div style=\"margin: 20px 0; padding: 10px; border: 1px solid #ccc; background: #f9f9f9;\">
    <b>Описание свойств инфоблока модуля:</b>
    <ul style=\"margin-top: 10px;\">
        <li>Свойство <b>CHECK_GET_PAR</b> (флажок, необязательно) — Учитывать строку GET-параметров (При отмеченной опции, в соответствии URL подстрока после знака вопроса будет учитываться, иначе обрезаться).</li>
        <li>Свойство <b>PAGE_URL</b> (строка, обязательно) — URL страницы фильтра, на которой будет выведен контент.</li>
        <li>Свойство <b>CONTENT_URL</b> (строка, обязательно) — URL страницы контента (URL страницы сайта, содержимое которой нужно вывести по запросу URL фильтра).</li>
        <li>Свойство <b>META_H1</b> (строка, необязательно) — Заголовок страницы H1.</li>
        <li>Свойство <b>META_TITLE</b> (строка, необязательно) — Заголовок браузера (Title).</li>
        <li>Свойство <b>META_KEYWORDS</b> (строка, необязательно) — Meta Keywords.</li>
        <li>Свойство <b>META_DESCRIPTION</b> (строка, необязательно) — Meta Description.</li>
        <li>Свойство <b>META_CANONICAL</b> (строка, необязательно) — Meta Canonical.</li>
        <li>Свойство <b>BC_NAME</b> (строка, необязательно) — Название пункта в цепочке навигации.</li>
        <li>Свойство <b>BC_AS_LINK</b> (флажок, необязательно) — Пункт цепочки навигации в виде ссылки.</li>
        <li>Свойство <b>HTTP_CODE</b> (список, необязательно) — HTTP-код ответа (например, 200, 301, 404 и др).</li>
    </ul>
</div>";
