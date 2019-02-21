<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/30
 * Time: 10:34
 * Email:499873958@qq.com
 */

namespace Xavier\Model\Driver;


use Xavier\Exceptions\DbException;
use Xavier\Facade\Config;

class Connect
{
    private $connect;

    public function __construct($key, $model)
    {
        $database = Config::get("database");
        $type     = $database['type']??false;
        if (!$type) {
            throw new DbException("it has not set databse");
        }
        $type  = ucwords($type);
        $class = "Xavier\\Model\\Driver\\{$type}\\Connect";
        if (!class_exists($class)) {
            throw new DbException("class {$class} is not exist");
        }
        $config        = Config::get($key);
        $this->connect = new $class($key, $model, $config);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->connect, $name)) {
            $this->connect->{$name}(...$arguments);
        } else {
            throw new DbException("methods {$name} is not exist");
        }
    }
}