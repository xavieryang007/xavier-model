<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/28
 * Time: 14:15
 * Email:499873958@qq.com
 */
declare(strict_types=1);

namespace Xavier;


class Config
{
    private $config = [];

    /**
     * 获取配置参数 为空则获取所有配置
     * @access public
     * @param  string $key 配置参数名（支持二级配置 . 号分割）
     * @return bool|mixed
     */
    public function get(string $key = "")
    {
        //为空返回所有配置
        if (empty($key)) {
            return $this->config;
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        $key    = explode('.', $key, 2);
        $key[0] = strtolower($key[0]);
        if (!isset($this->config[$key[0]]) && count($key) == 2) {
            return isset($this->config[$key[0]][$key[1]]) ?
                $this->config[$key[0]][$key[1]] :
                false;
        }
        return false;
    }

    public function initConfig(string $apppath)
    {
        if (is_dir($apppath . 'Config/')) {
            $path   = $apppath . 'Config/';
            $handle = opendir($path);    //打开目录
            while (($item = readdir($handle)) !== false) {
                //循环遍历目录
                if ($item != '.' && $item != '..') {
                    if (is_file($path . "/" . $item)) {
                        $arr ['file'] [] = $item;
                        $p               = strpos($item, '.');
                        if ($p !== false) {
                            $name          = substr($item, 0, $p);
                            $config[$name] = require($path . $name . '.php');
                        }
                    }
                }
            }
            closedir($handle);
            $this->config = $config;
        }
    }
}