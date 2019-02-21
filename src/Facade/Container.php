<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/27
 * Time: 18:00
 * Email:499873958@qq.com
 */
declare(strict_types=1);

namespace Xavier\Facade;


use Xavier\Facade;

/**
 * Class Container
 * @package Xavier
 * @method static string set(string $k, $v = null) 存储容器
 * @method static string get(string $k) 获取容器内容
 * @method static string make(string $class) 取出对象
 * @method static string release($class) 放回对象池
 */
class Container extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Xavier\Container::class;
    }
}