<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2019/1/3
 * Time: 9:54
 * Email:499873958@qq.com
 */

/**
 * 设置数组的key
 * @param $arr
 * @param $key
 * @param bool $unique
 * @return array
 */
function set_arr_key($arr, $key, $unique = true)
{
    $r = [];
    foreach ($arr as $v) {
        if ($unique) {
            $r[$v[$key]] = $v;
        } else {
            $r[$v[$key]][] = $v;
        }
    }
    return $r;
}

/**
 * 获取协程id
 */
function get_co_id()
{
    return \Swoole\Coroutine::getuid();
}