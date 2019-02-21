<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:11
 * Email:499873958@qq.com
 */

namespace Xavier\Cache\Driver;


use Xavier\Facade\Config;

abstract class Cache
{
    abstract public function get($key, \Closure $closure = null, $ttl = 0, $tags = []);

    abstract public function delRegex($key);

    abstract public function flush($tag);

    abstract public function set($key, $val, $ttl = 0, $tags = []);

    abstract public function del($key);

    protected function getTagKey($key, $tags = [])
    {
        $prefix = Config::get('cache.prefix')??"";
        if ($tags) {
            $prev = '';
            foreach ($tags as $tag) {
                $p = $this->get($tag);
                if (!$p) {
                    $p = $this->flush($tag);
                }
                $prev = md5($p . $prev);
            }

            return $prefix . $key . '#tag_' . $prev;
        } else {
            return $prefix . $key;
        }
    }

}