<?php

namespace Saa\Pictoptimizer\Optimizers;

use \Saa\Pictoptimizer\CliTools;
use \Saa\Pictoptimizer\AbstractOptimizer;

class OptipngOptimizer extends AbstractOptimizer
{
    public static function getMimeType()
    {
        return 'image/png';
    }

    public static function getToolName()
    {
        return 'optipng';
    }

    public function optimize($inputFile, $outputFile, $async = true)
    {
        $command = 'optipng -o3 -strip all -silent \'' . $outputFile . '\'';
        \Bitrix\Main\Diag\Debug::writeToFile('optimizer command:' . $command);
        if ($async) {
            CliTools::execAsync($command, true);
        } else {
            CliTools::execSyncro($command);
        }
        return true;
    }
}