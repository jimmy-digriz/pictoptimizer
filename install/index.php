<?php

use Bitrix\Main\Config\Option;

defined('B_PROLOG_INCLUDED') or die;

class saa_pict_optimize extends CModule
{
    public $MODULE_ID = 'saa.pict_optimize';
    public $MODULE_NAME = 'Ресайз и оптимизация изображений';
    public $MODULE_DESCRIPTION = '';

    public function __construct() {
        $arModuleVersion = [];
        require __DIR__.'/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
        $this->InstallFiles();
        $this->installOptions();

        // устанавливаем обработчики
        require(dirname(__FILE__).'/../classes/EventHandler.php');
        if(!empty(\Saa\Pictoptimizer\EventHandler::EVENT_LIST)){
            foreach(\Saa\Pictoptimizer\EventHandler::EVENT_LIST as $event){
                RegisterModuleDependences($event[0], $event[1], $this->MODULE_ID, $event[2], $event[3]);
            }
        }
    }

    public function DoUninstall() {
        $this->removeOptions();

        // сносим обработчики
        require(dirname(__FILE__).'/../classes/EventHandler.php');
        if(!empty(\Saa\Pictoptimizer\EventHandler::EVENT_LIST)){
            foreach(\Saa\Pictoptimizer\EventHandler::EVENT_LIST as $event){
                UnRegisterModuleDependences($event[0], $event[1], $this->MODULE_ID, $event[2], $event[3]);
            }
        }

        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
    }

    private function installOptions()
    {
        // заносим дефолтные значения в опции
        Option::set($this->MODULE_ID, 'enabled', '1');
    }

    private function removeOptions() {
        Option::delete($this->MODULE_ID);
    }

    public function InstallFiles() {
        //CopyDirFiles(realpath(__DIR__).'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/');
    }

    public function UnInstallFiles() {
        //DeleteDirFiles(realpath(__DIR__).'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/');
    }
}