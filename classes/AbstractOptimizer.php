<?php
namespace Saa\Pictoptimizer;

abstract class AbstractOptimizer extends AbstractCliTool
{
    abstract public static function getMimeType();

    abstract public function optimize($inputFile, $outputFile, $async = true);
}