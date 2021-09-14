<?php
namespace Saa\Pictoptimizer;

use Bitrix\Main\Config\Option;

class ModuleControl
{
    const MODULE_ID = 'saa.pict_optimize';

    static private $optimizeEnabled = true;

    /**
     * выключатель оптимизации
     */
    static public function disableOptimize()
    {
        self::$optimizeEnabled = false;
    }

    /**
     * включем оптимизацию обратно
     */
    static public function enableOptimize()
    {
        self::$optimizeEnabled = true;
    }

    /**
     * проверка состояния работы оптимизатора
     * @return bool
     */
    static public function isOptimizeEnabled()
    {
        return self::$optimizeEnabled;
    }

    // todo рефакторить
    /**
     * временный метод для проверки того, что все серверные тулзы установлены
     * @return bool
     */
    static public function isAllConfigurationOk()
    {
        $itsOk = true;
        $classes = ['Saa\Pictoptimizer\Optimizers\ConvertOptimizer', 'Saa\Pictoptimizer\Optimizers\OptipngOptimizer', 'Saa\Pictoptimizer\Resizers\MogrifyResizer'];
        foreach($classes as $className){
            $toolTag = 'exec_fatal_'.$className::getToolName();
            if(!$className::isAvailable()){
                $itsOk = false;
                break;
            }
        }

        return $itsOk;
    }

    //todo refactor

    /**
     * текущая конфигурация оптимизации
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function getCurrentConfiguration()
    {
        $config = [];
        $arMimeTypes = ['image/png', 'image/jpeg'];
        if(self::isModuleEnabled()){

            $of = new OptimizerFactory();
            foreach($arMimeTypes as $type){
                $optimizer = $of->getOptimizer($type);
                if(is_object($optimizer)){
                    $config['optimizer'][$type] = $optimizer::getToolName();
                }
            }
            unset($of);
        }

        return $config;
    }

    /**
     * включен ли модуль в настройках и есть ли активная конфигурация
     * @return bool
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function isModuleEnabled()
    {
        $isEnabled = false;
        $moduleEnabledOption = Option::get(self::MODULE_ID, 'enabled', '0');
        if($moduleEnabledOption == 1 && self::isAllConfigurationOk()){
            $isEnabled = true;
        }
        return $isEnabled;
    }

    /**
     * проверка используемых опций ресайза на предмет водяных знаков. если используется водяной знак, то отключать встроенный ресайз нельзя
     * @param $options
     *
     * @return bool
     */
    public static function isOptionsWithWatermark($options)
    {
        $res = false;
        // $options, //array($arSize, $resizeType, array(), false, $arFilters, $bImmediate)
        if(!empty($oneOption[4]) && is_array($options[4])){
            foreach($options[4] as $oneOption){
                if($oneOption['name'] == 'watermark'){
                    $res = true;
                    break;
                }
            }
        }

        return $res;
    }
}
