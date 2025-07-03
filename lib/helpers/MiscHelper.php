<?php

namespace DigitMind\QuickFilters\Helpers;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use CAdminMessage;
use CSite;

Loader::includeModule('digitmind.quickfilters');

class MiscHelper
{
    public static function getModuleId()
    {
        return 'digitmind.quickfilters';
    }

    public static function getAssetsPath($type)
    {
        $moduleId = self::getModuleId();
        $assetsPath = '';
        switch ($type) {
            case 'css':
            {
                $assetsPath = "/bitrix/css/{$moduleId}";
                break;
            }
            case 'js':
            {
                $assetsPath = "/bitrix/js/{$moduleId}";
                break;
            }
            case 'img':
            {
                $assetsPath = "/bitrix/images/{$moduleId}";
                break;
            }
        }
        return $assetsPath;
    }

    public static function getProgressBar($total, $value, $message)
    {
        $total = intval($total);
        $value = intval($value);
        $total1 = $total / 100;
        $progressValue = 100;
        if ($total1 > 0) {
            $progressValue = ($total - $value) / $total1;
        }

        CAdminMessage::ShowMessage(
            [
                'MESSAGE' => $message,
                'DETAILS' => '#PROGRESS_BAR#',
                'HTML' => true,
                'TYPE' => 'PROGRESS',
                'PROGRESS_WIDTH' => '600',
                'PROGRESS_TOTAL' => 100,
                'PROGRESS_VALUE' => $progressValue
            ]
        );
    }

    public static function getModuleUploadDirPath()
    {
        $uploadDirectoryName = Option::get('main', 'upload_dir');
        $moduleId = GetModuleID(__FILE__);

        return "/{$uploadDirectoryName}/{$moduleId}";
    }

    public static function getModuleUploadDirFullPath()
    {
        $documentRoot = Application::getDocumentRoot();
        $moduleUploadDirPath = self::getModuleUploadDirPath();

        return "{$documentRoot}{$moduleUploadDirPath}";
    }

    public static function removeGetParameters($urlString)
    {
        $urlString = trim($urlString);
        list($path) = explode('?', $urlString);
        return $path;
    }

    /**
     * Проверка URL на ответ с кодом 200
     *
     * @param $url
     * @param $includeRedirects - С учётом редиректов
     *
     * @return ?bool
     */
    public static function checkUrl200($url, $includeRedirects = true)
    {
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_FOLLOWLOCATION => $includeRedirects,
            CURLOPT_NOBODY => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $curlExecResult = curl_exec($curl);

        curl_close($curl);

        if ($curlExecResult !== false) {
            $curlInfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($curlInfo == 200) {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    /**
     * Содержит ли строка заданные подстроки
     *
     * @param $text
     * @param $textPieces
     *
     * @return bool
     */
    public static function checkStringContains($text, $textPieces)
    {
        $result = false;

        foreach ($textPieces as $textPiece) {
            if (stripos($text, $textPiece) !== false) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Содержит ли строка заданные подстроки по регулярным выражениям
     *
     * @param $text
     * @param $textPieces
     *
     * @return bool
     */
    public static function checkStringContainsRegex($text, $textPieces)
    {
        $result = false;

        foreach ($textPieces as $textPiece) {
            if (preg_match($textPiece, $text) === 1) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Проверка на необходимость добавления начального слеша, и добавление при необходимости
     *
     * @param $text
     * @return mixed|string
     */
    public static function checkFirstSlash($text)
    {
        if (!empty($text)) {
            if (preg_match('/^(?:(http:\/\/)|(https:\/\/)|(\/))/ui', $text) !== 1) {
                $text = "/$text";
            }
        }

        return $text;
    }

    /**
     * Возвращает полный URL текущей страницы
     *
     * @return string
     */
    public static function getFullCurUrl()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $server = Application::getInstance()->getContext()->getServer();

        $host = '';
        if ($request->isHttps()) {
            $host = 'https://';
        } else {
            $host = 'http://';
        }

        $host .= $server->getHttpHost();
        $requestUri = $request->getRequestUri();

        return "{$host}{$requestUri}";
    }

    /**
     * Возвращает идентификаторы доступных сайтов
     *
     * @return array
     */
    public static function getSiteIds(): array
    {
        $returnResult = [];

        $dbResult = CSite::GetList(
            'sort',
            'asc',
            ['ACTIVE' => 'Y'],
        );
        while ($arrResult = $dbResult->fetch()) {
            if (!empty($arrResult['LID'])) {
                $returnResult[] = $arrResult['LID'];
            }
        }

        return $returnResult;
    }

    /**
     * Возвращает HTTP-коды ответов
     *
     * @return array
     */
    public static function getHttpCodes(): array
    {
        return [
            '100' => '100 Continue',
            '101' => '101 Switching Protocol',
            '102' => '102 Processing',
            '103' => '103 Early Hints',
            '200' => '200 OK',
            '201' => '201 Created',
            '202' => '202 Accepted',
            '203' => '203 Non-Authoritative Information',
            '204' => '204 No Content',
            '205' => '205 Reset Content',
            '206' => '206 Partial Content',
            '207' => '207 Multi-Status',
            '208' => '208 Already Reported',
            '226' => '226 IM Used',
            '300' => '300 Multiple Choices',
            '301' => '301 Moved Permanently',
            '302' => '302 Found',
            '303' => '303 See Other',
            '304' => '304 Not Modified',
            '307' => '307 Temporary Redirect',
            '308' => '308 Permanent Redirect',
            '400' => '400 Bad Request',
            '401' => '401 Unauthorized',
            '402' => '402 Payment Required',
            '403' => '403 Forbidden',
            '404' => '404 Not Found',
            '405' => '405 Method Not Allowed',
            '406' => '406 Not Acceptable',
            '407' => '407 Proxy Authentication Required',
            '408' => '408 Request Timeout',
            '409' => '409 Conflict',
            '410' => '410 Gone',
            '411' => '411 Length Required',
            '412' => '412 Precondition Failed',
            '413' => '413 Payload Too Large',
            '414' => '414 URI Too Long',
            '415' => '415 Unsupported Media Type',
            '416' => '416 Range Not Satisfiable',
            '417' => '417 Expectation Failed',
            '418' => '418 I\'m a teapot',
            '421' => '421 Misdirected Request',
            '422' => '422 Unprocessable Entity',
            '423' => '423 Locked',
            '424' => '424 Failed Dependency',
            '425' => '425 Too Early',
            '426' => '426 Upgrade Required',
            '428' => '428 Precondition Required',
            '429' => '429 Too Many Requests',
            '431' => '431 Request Header Fields Too Large',
            '451' => '451 Unavailable For Legal Reasons',
            '500' => '500 Internal Server Error',
            '501' => '501 Not Implemented',
            '502' => '502 Bad Gateway',
            '503' => '503 Service Unavailable',
            '504' => '504 Gateway Timeout',
            '505' => '505 HTTP Version Not Supported',
            '506' => '506 Variant Also Negotiates',
            '507' => '507 Insufficient Storage',
            '508' => '508 Loop Detected',
            '510' => '510 Not Extended',
            '511' => '511 Network Authentication Required'
        ];
    }
}
