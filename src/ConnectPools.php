<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:03
 * Email:499873958@qq.com
 */

namespace Xavier;


use Swoole\Coroutine\Channel;


class ConnectPools
{
    private $pools = [];

    private $connect_count = 0;

    private $sw = [];

    /**
     * 60秒内无请求 将逐渐释放连接
     * @var int
     */
    private $free_time = 60;

    private static $last_use_time = 0;

    /**
     * push对象进入连接池
     * @param $obj
     * @param bool $s
     */
    public function push($obj, $s = false, $key = "default")
    {
        $id = $key . '_' . get_co_id();
        if (isset($this->sw[$id])) {
            if ($s || $obj !== $this->sw[$id]) {
                unset($this->sw[$id]);
                $this->pools[$key]->push($obj);
            }
        } else {
            $this->pools[$key]->push($obj);
        }
    }

    /**
     * @param bool $sw 是否事物
     * @return \PDO | \Redis
     */
    public function pop($sw = false, $key = "default")
    {
        $key   = $key;
        $co_id = $key . '_' . get_co_id();
        if (isset($this->sw[$co_id])) {
            return $this->sw[$co_id];
        }
        $rs = $this->getCliRes($key);
        if ($sw) {
            $this->sw[$co_id] = $rs;
        }
        return $rs;
    }

    private function getCliRes($key)
    {
        $time = time();
        if (!isset($this->pools[$key])) {
            $this->pools[$key] = new Channel($this->config['max_connect_count']);
        }
        $sp = $this->pools[$key];

        if ($sp->isEmpty()) {
            if ($this->connect_count < $this->config['max_connect_count']) {
                $this->connect_count++;
                $sp->push($this->createRes());
            }
        } else if ($this->last_use_time > 0 && ($this->last_use_time + $this->free_time) < $time && $sp->length() > 1) {
            $sp->pop();
            $this->connect_count--;
        }
        $this->last_use_time = $time;
        return $sp->pop();
    }

    private function get_co_id()
    {

    }
}