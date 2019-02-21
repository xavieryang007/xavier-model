<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:18
 * Email:499873958@qq.com
 */

namespace Xavier\Cache;


use Xavier\Exceptions\BaseException;
use Xavier\Pools;

class Redis extends Cache
{
    use Pools;
    protected $key = '';

    private $config = [];

    private $retry_count = 3;

    public function __construct($config, $key = 'default')
    {
        $this->config = $config;
        $this->setConnection($key);
    }

    public function __call($name, $arguments)
    {
        $rs = $this->pop();
        try {
            $ret = $rs->$name(...$arguments);
            $this->push($rs);
            $this->retry_count = 3;
            return $ret;
        } catch (\RedisException $e) {
            return $this->retry($name, $arguments, $e->getMessage(), $e->getCode());
        }
    }

    private function retry($name, $arguments, $msg, $code)
    {
        Log::warn('retry ' . $name);
        self::$connect_count--;
        if ($this->retry_count > 0) {
            return $this->{$name}(...$arguments);
        } else {
            $this->retry_count = 3;
            throw new \Exception($msg, $code);
        }
    }


    public function setConnection($key)
    {
        $this->key    = $key;
        return $this;
    }

    /**
     * @return \Redis
     */
    private function createRes()
    {
        $r    = new \Redis();
        $host = $this->config['host'];
        $port = $this->config['port'];
        if (!$host || !$port) {
            throw new BaseException("Redis host or port is not set");
        }
        $r->connect($host, $port, 0);
        return $r;
    }


    public function get($key, \Closure $closure = null, $ttl = null, $tags = [])
    {
        try {
            $rs  = $this->pop();
            $val = $rs->get($this->getTagKey($key, $tags));
            if ((!$val) && $closure) {
                $val = $closure();
                $this->set($key, $val, $ttl, $tags);
            } else if ($val) {
                $val = unserialize($val);
            }
            $this->push($rs);
            $this->retry_count = 3;
            return $val;
        } catch (\RedisException $e) {
            return $this->retry('get', func_get_args(), $e->getMessage(), $e->getCode());
        }
    }

    public function del($key)
    {
        try {
            if (is_string($key)) {
                $prefix = $this->config['prefix']??"";
                $key    = $prefix . $key;
            }
            $rs  = $this->pop();
            $ret = $rs->del($key);
            $this->push($rs);
            $this->retry_count = 3;
            return $ret;
        } catch (\RedisException $e) {
            return $this->retry('del', func_get_args(), $e->getMessage(), $e->getCode());
        }
    }

    public function delRegex($key)
    {
        return $this->del($this->keys($key));
    }

    public function flush($tag)
    {
        $id = md5(uuid());
        $this->set($tag, $id);
        return $id;
    }

    public function set($key, $val, $ttl = null, $tags = [])
    {
        try {
            $rs  = $this->pop();
            $ret = $rs->set($this->getTagKey($key, $tags), serialize($val), $ttl);
            $this->push($rs);
            $this->retry_count = 3;
            return $ret;
        } catch (\RedisException $e) {
            return $this->retry('set', func_get_args(), $e->getMessage(), $e->getCode());
        }

    }

}
