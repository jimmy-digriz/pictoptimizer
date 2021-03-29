<?php
namespace Saa\Pictoptimizer;

class CliTools
{
    const BACKGROUND_TAIL_SILENT = ' > /dev/null 2>&1 &';
    const BACKGROUND_TAIL = ' > /dev/null &';

    // todo это отсюда бы убрать куда-то еще
    /**
     * создает диру под указанный файл
     * @param $filename
     * @return bool
     */
    public static function makeDirForFile($filename){
        $res = false;
        $dirToCreate = dirname($filename); // realpath все ломает?!
        if(!file_exists($dirToCreate)){
            return mkdir($dirToCreate, 0777, true);
        } else {
            return true;
        }
    }

    /**
     * проверка существования консольной команды/утилиты
     * @param $command
     * @return bool
     */
    public static function isCommandExist($command){
        exec($command, $arReturn, $code);
        if($code == 0 || $code == 1){ // 0 success 1 syntax errors
            return true;
        } else {
            return false;
        }
    }

    /**
     * запуск команды синхронно
     * @param $command
     * @return bool
     */
    public static function execSyncro($command){
        exec($command, $arReturn, $code);
        if($code == 0){
            return true;
        } else {
            return false;
        }
    }

    /**
     * запуск команды асинхронно
     * @param $command
     * @param $isSilentMode
     * @return bool
     */
    public static function execAsync($command, $isSilentMode){
        if($isSilentMode === true){
            exec($command . self::BACKGROUND_TAIL_SILENT);
        } else {
            exec($command . self::BACKGROUND_TAIL);
        }

        return true;
    }
}
