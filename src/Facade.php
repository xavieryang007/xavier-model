<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/27
 * Time: 18:00
 * Email:499873958@qq.com
 */
declare(strict_types=1);

namespace Xavier;

abstract class Facade
{
    private static $accessor = [];

    abstract protected static function getFacadeAccessor();

    protected static function initArgs(...$arg)
    {
        if (!empty($arg)) {
            return $arg;
        }
        return [];
    }

    public static function __callStatic($method, $parameters)
    {
        $cl = static::getFacadeAccessor();
        if (is_string($cl)) {
            if (!isset(self::$accessor[$cl])) {
                self::$accessor[$cl] = new $cl(...static::initArgs());
            }
        }

        if (is_object($cl)) {
            $class               = $cl;
            $cl                  = get_class($cl);
            self::$accessor[$cl] = $class;
        }


        return self::$accessor[$cl]->$method(...$parameters);
    }

    public static function clear($class)
    {
        unset(self::$accessor[$class]);
    }

}
