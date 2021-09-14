<?php

namespace Saa\Pictoptimizer\Optimizers;

use \Saa\Pictoptimizer\CliTools;
use \Saa\Pictoptimizer\AbstractOptimizer;
use Saa\Pictoptimizer\ModuleControl;

class PngquantOptimizer extends AbstractOptimizer
{
    public static function getMimeType()
    {
        return 'image/png';
    }

    public static function getToolName()
    {
        return 'pngquant';
    }

    public function optimize($inputFile, $outputFile, $async = true)
    {
        $command = 'pngquant --quality 60-90 --force --speed 3 --output \'' . $outputFile . '\' \'' . $outputFile . '\'';
        ModuleControl::writeLog('optimizer command:' . $command);
        if ($async) {
            CliTools::execAsync($command, true);
        } else {
            CliTools::execSyncro($command);
        }
        return true;
    }
}