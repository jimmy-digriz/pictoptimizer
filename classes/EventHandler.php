<?php
namespace Saa\Pictoptimizer;

class EventHandler
{
    const MODULE_ID = 'saa.pict_optimize';

    const EVENT_LIST = [
        ['main', 'OnBeforeResizeImage', '\\'.self::class, 'onBeforeResizeImageHandler'],
        ['main', 'OnAfterResizeImage', '\\'.self::class, 'onAfterResizeImageHandler'],
        ['main', 'OnFileSave', '\\'.self::class, 'onFileSaveHandler'],
    ];



    /*
     * перехватывая это мы отменяем битриксовый ресайз, просто для экономии времени
     */
    public static function onBeforeResizeImageHandler(
        $file,
        $options, //array($arSize, $resizeType, array(), false, $arFilters, $bImmediate)
        &$callbackData,
        &$needResize,
        &$sourceImageFile,
        &$cacheImageFileTmp
    ) {
        if (ModuleControl::isModuleEnabled()) {
            $GLOBALS['resizeStart'] = microtime(true);

            $needResize = false; // запрещаем ресайз
            return true; // обрываем цепочку обработчиков
        }
    }

    public static function onAfterResizeImageHandler(
        $file,
        $options,
        &$callbackData, // пусто
        &$cacheImageFile, // тут относительный путь к кеш файлу, который БУДЕТ ВОЗВРАЩАТЬ функция ресайза
        &$cacheImageFileTmp, // тут ПОЛНЫЙ путь в кешфайлу
        &$arImageSize // ?? тут пусто
    ) {
        if(ModuleControl::isModuleEnabled()){
            $fileTo = $cacheImageFileTmp;
            $fileFrom = $_SERVER['DOCUMENT_ROOT'].$file['SRC'];

            $resizeX = $options[0]['width'];
            $resizeY = $options[0]['height'];
            $resizeType = $options[1]?:1; // BX_RESIZE_IMAGE_PROPORTIONAL

            if($resizeType == BX_RESIZE_IMAGE_PROPORTIONAL_ALT){
                $resizeX = max($options[0]['width'], $options[0]['height']);
                $resizeY = min($options[0]['width'], $options[0]['height']);
            }

            if ( ! empty($fileTo) && CliTools::makeDirForFile($fileTo)) {
                if(copy($fileFrom, $fileTo)){
                    $rf = new ResizerFactory();
                    $resizer = $rf->getResizer();
                    if (is_object($resizer)) {
                        // внимание. работаем только с конечным файлом, не с исходником
                        $resizeResult = $resizer->resize($fileTo, $fileTo, $resizeX, $resizeY, $resizeType);
                        if ($resizeResult && ModuleControl::isOptimizeEnabled()) {
                            $of = new OptimizerFactory();
                            $optimizer = $of->getOptimizer($file['CONTENT_TYPE']);
                            if (is_object($optimizer)) {
                                // внимание. работаем только с конечным файлом, не с исходником
                                $optimizer->optimize($fileTo, $fileTo);
                            }
                        }

                        //todo куда-то это деть
                        $resizeTime = microtime(true) - $GLOBALS['resizeStart'];
                    }

                    // дополнительно - если ресайзили, то необходимо поправить тот самый путь к файлу-результату
                    // который собирается вернуть ResizeImageGet
                    // потому что в нем сейчас неизменненное изображение, т.к. выше мы отменяли битрикосвый ресайз
                    $cacheImageFile = str_replace($_SERVER['DOCUMENT_ROOT'], '', $fileTo);
                }
            }

            // todo есть мысль, что если что-то сломалось в процессе, то следует сделать так, чтобы результат ресайза вернуслся пустой. Либо вернул исходник
        }

    }


    public static function onFileSaveHandler(
        &$arrFileInfo,
        $fileName,
        $module
    ) {
        if(ModuleControl::isModuleEnabled()){
            if (strpos($arrFileInfo['type'], 'image') === false || ! in_array($module, ['iblock', 'medialibrary'])) {
                return false;
            }

            $sourceFile = $arrFileInfo['tmp_name'];

            if (ModuleControl::isOptimizeEnabled()) {
                $of = new OptimizerFactory();
                $optimizer = $of->getOptimizer($arrFileInfo['type']);
                if (is_object($optimizer)) {
                    $async = false;
                    // эту оптимизацию запускаем в синхронном режиме - файл нужно вернуть тут же
                    $optimizer->optimize($sourceFile, $sourceFile, $async);

                    clearstatcache(true, $sourceFile);
                    $sizeAfter = filesize($sourceFile);
                    $arrFileInfo = \CFile::MakeFileArray($sourceFile);

                }
            }
        }

    }
}