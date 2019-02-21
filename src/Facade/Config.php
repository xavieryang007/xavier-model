<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:13
 * Email:499873958@qq.com
 */

namespace Xavier\Facade;

use Xavier\Facade;

/**
 * Class Config
 * @package Xavier
 * @method static string get(string $key) 获取容器内容
 * @method static string initConfig(string $path) 初始化配置信息
 */
class Config extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Container::get(\Xavier\Config::class);
    }
}