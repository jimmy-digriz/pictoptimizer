<?php

defined('B_PROLOG_INCLUDED') or die;

\Bitrix\Main\Loader::registerAutoLoadClasses('saa.pict_optimize', [
    'Saa\Pictoptimizer\EventHandler' => 'classes/EventHandler.php',
    'Saa\Pictoptimizer\AbstractOptimizer' => 'classes/AbstractOptimizer.php',
    'Saa\Pictoptimizer\AbstractResizer' => 'classes/AbstractResizer.php',
    'Saa\Pictoptimizer\AdminTools' => 'classes/AdminTools.php',
    'Saa\Pictoptimizer\CliTools' => 'classes/CliTools.php',
    'Saa\Pictoptimizer\ModuleControl' => 'classes/ModuleControl.php',
    'Saa\Pictoptimizer\OptimizerFactory' => 'classes/OptimizerFactory.php',
    'Saa\Pictoptimizer\ResizerFactory' => 'classes/ResizerFactory.php',
    'Saa\Pictoptimizer\Optimizers\ConvertOptimizer' => 'classes/Optimizers/ConvertOptimizer.php',
    'Saa\Pictoptimizer\Optimizers\OptipngOptimizer' => 'classes/Optimizers/OptipngOptimizer.php',
    'Saa\Pictoptimizer\Optimizers\PngquantOptimizer' => 'classes/Optimizers/PngquantOptimizer.php',
    'Saa\Pictoptimizer\Resizers\MogrifyResizer' => 'classes/Resizers/MogrifyResizer.php',
    'Saa\Pictoptimizer\AbstractCliTool' => 'classes/AbstractCliTool.php'
]);

//todo пока просто заглушка, так как все утилиты определены статически
$classes = ['Saa\Pictoptimizer\Optimizers\ConvertOptimizer', 'Saa\Pictoptimizer\Optimizers\OptipngOptimizer', 'Saa\Pictoptimizer\Resizers\MogrifyResizer'];
foreach($classes as $className){
    $toolTag = 'exec_fatal_'.$className::getToolName();
    if(!$className::isAvailable()){
        \Saa\Pictoptimizer\AdminTools::addAdminNotify('На сервере не установлена утилита "'.$className::getToolName().'"', $toolTag);
    } else {
        \Saa\Pictoptimizer\AdminTools::removeAdminNotifyByTag($toolTag);
    }
}
