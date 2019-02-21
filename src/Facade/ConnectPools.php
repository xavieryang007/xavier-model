<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:09
 * Email:499873958@qq.com
 */

namespace Xavier\Facade;


use Xavier\Facade;

/**
 * Class ConnectPools
 * @package Xavier
 * @method static string push($obj, bool $sw,string $key) 存储容器
 * @method static string pop(bool $sw,string $key) 获取容器内容
 */
class ConnectPools extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Container::get(\Xavier\ConnectPools::class);
    }
}