<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:38
 * Email:499873958@qq.com
 */

namespace Xavier;


use Xavier\Facade\ConnectPools;

trait Pools
{
    public function push($obj, $sw = false)
    {
        ConnectPools::pop($obj, $sw, $this->key);
    }

    public function pop($sw = false)
    {
        return ConnectPools::pop($sw, $this->key);
    }
}