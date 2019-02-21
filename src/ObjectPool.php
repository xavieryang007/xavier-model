<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/28
 * Time: 12:12
 * Email:499873958@qq.com
 */
declare(strict_types=1);

namespace Xavier;


use Xavier\Exceptions\BaseException;

/**
 * 对象池
 * Class Pool
 * @package Xavier
 */
class ObjectPool
{
    private $map;
    private $pool_count = [];

    public function __construct()
    {
        $this->map = [];
    }

    /**
     * 获取一个
     * @param $class
     * @return mixed
     * @throws BaseException
     */
    public function pop($class)
    {
        $pool = $this->map[$class]??null;
        if ($pool == null) {
            $pool = $this->applyNewPool($class);
        }
        if (!$pool->isEmpty()) {
            return $pool->shift();
        } else {
            $this->addNewCount($class);
            return new $class;
        }
    }

    private function addNewCount($name)
    {
        if (isset($this->pool_count[$name])) {
            $this->pool_count[$name]++;
        } else {
            $this->pool_count[$name] = 1;
        }
    }

    private function applyNewPool($class)
    {
        if (array_key_exists($class, $this->map)) {
            throw new BaseException('the name is exists in pool map');
        }
        $this->map[$class] = new \SplStack();
        return $this->map[$class];
    }

    /**
     * 返还一个
     * @param $classInstance
     * @throws BaseException
     */
    public function push($classInstance)
    {
        $class = get_class($classInstance);
        $pool  = $this->map[$class]??null;
        if ($pool == null) {
            $pool = $this->applyNewPool($class);
        }
        $pool->push($classInstance);
    }

    /**
     * 获取状态
     */
    public function getStatus()
    {
        $status = [];
        foreach ($this->map as $key => $value) {
            $status[$key . '[pool]'] = count($value);
            $status[$key . '[new]']  = $this->pool_count[$key]??0;
        }
        return $status;
    }
}