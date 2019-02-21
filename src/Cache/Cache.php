<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/30
 * Time: 10:03
 * Email:499873958@qq.com
 */

namespace Xavier\Cache;


use Xavier\Facade\Config;
use Xavier\Facade\Container;

class Cache
{
    private $cache;

    public function __construct($key = 'default')
    {
        $database = Config::get("cache");
        $type     = $database['type']??false;
        if (!$type) {
            throw new DbException("it has not set databse");
        }
        $type  = ucwords($type);
        $class = "Xavier\\Cache\\Driver\\{$type}";
        if (!class_exists($class)) {
            throw new DbException("class {$class} is not exist");
        }
        $config      = Config::get($key);
        $this->cache = new $class($config, $key);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->cache, $name)) {
            $this->cache->{$name}(...$arguments);
        } else {
            throw new DbException("methods {$name} is not exist");
        }
    }
}