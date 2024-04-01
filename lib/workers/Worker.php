<?php

namespace DigitMind\Sample\Workers;

use DigitMind\Sample\Helpers\MiscHelper;

class Worker
{
    public static function doWork($someInfo)
    {
        $moduleUploadDirPath = MiscHelper::getModuleUploadDirPath();

        $moduleUploadDirPath = trim($moduleUploadDirPath);
        $filePath = "{$moduleUploadDirPath}/temp.txt";
        file_put_contents($filePath, $someInfo);

        return 'success';
    }
}
