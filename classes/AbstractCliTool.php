<?php

namespace Saa\Pictoptimizer;

abstract class AbstractCliTool
{
    abstract static public function getToolName();

    public static function isAvailable()
    {
        if(CliTools::isCommandExist(static::getToolName())){
            return true;
        } else {
            return false;
        }
    }
}