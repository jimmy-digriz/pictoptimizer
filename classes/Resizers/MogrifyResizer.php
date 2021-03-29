<?php
namespace Saa\Pictoptimizer\Resizers;

use Saa\Pictoptimizer\CliTools;
use Saa\Pictoptimizer\AbstractResizer;

class MogrifyResizer extends AbstractResizer
{
    public function resize($inputFile, $outputFile, $x, $y, $algorithm)
    {
        $resizeDimensions = $x.'x'.$y;
        if($algorithm == BX_RESIZE_IMAGE_EXACT){
            //$resize = '-crop \''.$resizeDimensions.'\' -gravity center';
            //$resize = '-resize \'' . $resizeDimensions .'!<\' -gravity center';
            //$resize = '-thumbnail '.$resizeDimensions.'^ -gravity center';
            $resize = '-resize '.$resizeDimensions.'^ -gravity Center -extent '.$resizeDimensions;
        } else {
            $resize = '-resize \'' . $resizeDimensions .'\'';
        }

        $command = 'mogrify '.$resize.' \'' . $outputFile . '\'';
        \Bitrix\Main\Diag\Debug::writeToFile('resizer command:' . $command);
        $result = CliTools::execSyncro($command);
        return $result;
    }

    public static function getToolName()
    {
        return 'mogrify';
    }
}