<?php

namespace Saa\Pictoptimizer\Optimizers;

use Saa\Pictoptimizer\AbstractOptimizer;
use Saa\Pictoptimizer\CliTools;
use Saa\Pictoptimizer\ModuleControl;

class ConvertOptimizer extends AbstractOptimizer
{
    const JPEG_QUALITY = 80;

    public function optimize($inputFile, $outputFile, $async = true)
    {
        $command = 'convert \'' . $inputFile . '\' -sampling-factor 4:2:0 -strip -quality ' . self::JPEG_QUALITY . ' \'' . $outputFile . '\'';
        ModuleControl::writeLog('optimizer command:' . $command);
        if ($async) {
            CliTools::execAsync($command, true);
        } else {
            CliTools::execSyncro($command);
        }

        return true;
    }

    public static function getMimeType()
    {
        return 'image/jpeg';
    }

    public static function getToolName()
    {
        return 'convert';
    }
}