<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/27
 * Time: 17:49
 * Email:499873958@qq.com
 */
declare(strict_types=1);

namespace Xavier;

class Container
{
    private $instance = [];
    private $objectpool = ObjectPool::class;

    /**
     * @param string $k
     * @param null $c
     */
    public function set(string $k, $c = null)
    {
        if (empty($c)) {
            $this->instance[$k] = new $k();
        }else{
            $this->instance[$k] = $c;
        }
    }

    public function get(string $k, ...$args)
    {
        $ret = $this->instance[$k];
        if (is_callable($ret)) {
            return $ret($this, ...$args);
        }
        if (is_string($ret) && class_exists($ret)) {
            return new $ret();
        }
        return $ret;
    }

    /**
     * 对象池内返回对象
     * @param string $class
     * @return mixed
     */
    public function make(string $class, ...$args)
    {
        if (!isset($this->instance[$this->objectpool])) {
            $this->instance[$this->objectpool] = new $this->objectpool();
        }
        $objectpool = $this->instance[$this->objectpool];
        if (method_exists($this->instance[$this->objectpool], "init")) {
            $this->instance[$this->objectpool]->init($args);
        }
        return $objectpool->pop($class);
    }

    /**
     * 将对象放回对象池
     * @param $class
     */
    public function release($class)
    {
        if (!isset($this->instance[$this->objectpool])) {
            $this->instance[$this->objectpool] = new $this->objectpool();
        }
        $objectpool = $this->instance[$this->objectpool];
        $objectpool->push($class);
    }

}