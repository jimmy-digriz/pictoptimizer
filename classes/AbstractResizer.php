<?php
namespace Saa\Pictoptimizer;

abstract class AbstractResizer extends AbstractCliTool
{
    abstract public function resize($inputFile, $outputFile, $x, $y, $algorithm);
}