<?php

namespace Saa\Pictoptimizer;

class AdminTools
{
    /**
     * Выводит в админке незакрываемое оповещение
     * @param $message
     * @param $tag
     * @return bool
     */
    public static function addAdminNotify($message, $tag)
    {
        if ( ! empty($message) && ! empty($tag)) {
            $id = \CAdminNotify::Add([
                'MESSAGE' => $message,
                'TAG' => $tag,
                'MODULE_ID' => 'main',
                'ENABLE_CLOSE' => 'N',
                'TYPE' => \CAdminNotify::TYPE_ERROR, //TYPE_ERROR
            ]);
            return true;
        }
        return false;
    }

    /**
     * Убирает из админки незакрываемое оповещение
     * @param $tag
     * @return bool
     */
    public static function removeAdminNotifyByTag($tag)
    {
        if ( ! empty($tag)) {
            \CAdminNotify::DeleteByTag($tag);
            return true;
        }
        return false;
    }
}