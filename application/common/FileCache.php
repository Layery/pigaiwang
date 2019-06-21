<?php

namespace app\common;

use think\facade\Cache;

class FileCache
{

    /**
     * 设置缓存
     *
     * @param string $key 存储的唯一key
     * @param array $data 需要存储的内容
     * @param int $time  缓存时间
     * @param string $store 存储目录
     * @return bool
     */
    public static function set($key, $data, $time = 0, $store = 'default')
    {
        return Cache::store($store)->set($key, $data, $time);
    }

    /**
     * 获取缓存数据
     * @static :静态方法
     * @param string $key 缓存的唯一key
     * @return $data
     */
    public static function get($key, $store = 'default')
    {
        $data = Cache::store($store)->get($key);
        return $data ? $data : '';
    }

    /**
     * 删除文件
     * @param        $path
     * @return bool
     */
    public static function del($key, $store = 'default')
    {
        return Cache::store($store)->rm($key);
    }

}

?>