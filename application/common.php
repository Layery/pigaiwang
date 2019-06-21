<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件

if (!function_exists('p')) {
    /**
     * @param mixed $data
     * @param null $status
     */
    function p($data, $status = null)
    {
        echo "<pre/>";
        if ($data == null || $status) {
            var_dump($data);
        } else {
            print_r($data);
        }
        exit("</pre>");
    }
}

if (!function_exists('getIp')) {
    /*
     * 获取ip地址
     */
    function getIp()
    {
        $ipaddressnew = $ipaddress = '0.0.0.0';
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (array_key_exists('HTTP_X_FORWARDED', $_SERVER))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (array_key_exists('HTTP_FORWARDED', $_SERVER))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (array_key_exists('REMOTE_ADDR', $_SERVER))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        $ipaddress_arr = explode(",", $ipaddress);
        $ipaddressnew = trim($ipaddress_arr[0]);
        return $ipaddressnew;
    }
}

if (!function_exists('escapeInput')) {
    /**
     * 框架input函数封装转义
     *
     * @param string $key
     * @param null $default
     * @param string $filter
     * @return array|string
     */
    function escapeInput($key = '', $default = null, $filter = '')
    {
        $temp = call_user_func_array('input', [$key, $default, $filter]);
        if (is_array($temp)) {
            return array_map('addslashes', $temp);
        } else {
            return addslashes($temp);
        }
    }
}

if (!function_exists('getPsw')) {
    /**
     * 密码加密方法
     * 双md5 加密
     * @param string $pws
     * @param string $salt
     */
    function getPsw($pws = '', $salt = '')
    {
        if ($pws == '' || $salt == '') return '';
        return md5(md5($pws) . $salt);
    }
}

if (!function_exists('sendCurl')) {
    /**
     * CURL请求
     * author wangwei
     * time 20180809
     * @param $url 必填
     * @param $method 请求类型 get post delete 等
     * @param $data 是否post数据
     * @param $data header头信息
     */
    function sendCurl($url, $method = 'GET', $data = array(), $headers = array())
    {
        if (!$url) return false;
        $method = strtoupper($method);//请求类型转换成大写
        $ch = curl_init();//初始化
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $timeout = 10;//设置超时时间10秒
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        switch ($method) {
            case "GET" :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case "PUT" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
        }
        if (!empty($data) && $method == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (strpos($url, 'https://') === 0) {//https
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $output = curl_exec($ch);
        curl_close($ch);//释放curl句柄
        return $output;
    }
}

if (!function_exists('renderTree')) {
    /**
     * 渲染树菜单
     *
     * @param $data
     * @param int $id
     * @param null $currRoleMenu
     * @return array
     */
    function renderTree($data, $id = 0, $currRoleMenu = NULL)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            $v['text'] = $v['text']. '<span style="display: none" class="data-span" data-id="'. $v['id']. '|'. $v['p_id'] .'"></span>';
            // node全局配置
            $nodeConfig = [
                #'icon' => "glyphicon glyphicon-stop",
                #'backColor' => "white",
                #'color' => "black",
                #'selectedIcon' => 'glyphicon glyphicon-stop',
                'selectable' => 0,
                'Id' => $v['id'],
                'nodeId' => $v['text']
            ];
            $v = $v + $nodeConfig;
            if ($v['p_id'] == $id) {
                if (in_array($v['id'], $currRoleMenu)) {
                    // node 子孙配置
                    $v['state'] = [
                        'checked' => 1
                    ];
                }
                $sons = renderTree($data, $v['id'], $currRoleMenu);
                if (!empty($sons)) {
                    $v['nodes'] = $sons;
                }
                $tree[] = $v;
            }
        }
        return $tree;
    }
}

if (!function_exists('array_sort')) {
    /**
     * 多维数组按自定义键排序
     *
     * @param $array
     * @param $keys
     * @param string $type
     * @return array
     */
    function array_sort($array, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($array as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $array[$k];
        }
        return $new_array;
    }
}

if (!function_exists('exportCsv')) {
    /**
     * 导出csv文件
     *
     * @param $fileName
     * @param array $headArr
     * @param array $data
     */
    function exportCsv($fileName, array $headArr, array $data)
    {
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename={$fileName}.csv");
        header('Cache-Control: max-age=0');
        if (!$data) return;

        $fp = fopen('php://output', 'a');
        $head = array_map(create_function('$a', 'return mb_convert_encoding($a, "GBK", "UTF-8");'), $headArr);
        fputcsv($fp, $head);
        foreach ($data as $v) {
            $v = array_map(create_function('$a', 'return mb_convert_encoding($a, "GBK", "UTF-8");'), $v);
            $v = array_intersect_key(array_merge($head, $v), $head);
            fputcsv($fp, $v);
        }
    }
}

if (!function_exists('getSpecialConfig')) {
    /**
     * 获取特殊需求配置
     *
     * @param $key
     * @deprecated 作废
     * @return bool|mixed
     */
    function getSpecialConfig($key)
    {
        $config = [
            // 部门配置
            'hy_section' => [
                // 部门级别
                'section_level' => [
                    2 => '二级部门',
                    3 => '三级部门'
                ],
                // 一级部门
                'top_section' => [
                    1 => '悦美好医万柳店',
                    2 => '悦美好医望京店'
                ]
            ],
            // 特殊权限需求配置
            'special_process' => [
                ['id' => DATA_PROCESS, 'text' => '数据权限', 'p_id' => 0],
                ['id' => EXPORT_DATA, 'text' => '导出数据', 'p_id' => DATA_PROCESS],
                ['id' => DISPLAY_PHONE, 'text' => '显示客户手机号', 'p_id' => DATA_PROCESS],
            ],
        ];

        if (array_key_exists($key, $config)) {
            return $config[$key];
        } else {
            return false;
        }
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * 检测是否是超管权限
     *
     * @return bool
     */
    function isSuperAdmin()
    {
        if (session('userinfo')) {
            return session('userinfo')['role_id'] == SUPER_ADMIN_ID;
        }
        return false;
    }
}

if (!function_exists('easyGetSuns')) {
    /**
     * 获取子孙列表
     *
     * @param $list
     * @param $id
     * @return array
     */
    function easyGetSuns($list, $id)
    {
        $result = []; //最终结果
        $result[$id] = 0;
        foreach ($list as $k => $v) {
            if (isset($result[$v['p_id']]) && !isset($result[$v['id']])) {
                // $result[$v['active_uid']] += 1;
                $result[$v['id']] = $v;
            }
        }
        unset($result[$id]);

        return $result;
    }
}
if (!function_exists('getImageUrl')) {
    /*
     * 获取图片地址
     * 统一方法
     * $param $url 图片路径
     * $return 带域名的路径
     */
    function getImageUrl($url=''){
        return $url ? rtrim(IMG,'/').$url : '';
    }
}

if (!function_exists('arrayCoding')) {
    /**
     * 转换数组内字符编码
     *
     * @param $array
     * @param $inCharset
     * @param $outCharset
     * @return bool
     */
    function arrayCoding ($array, $inCharset, $outCharset) {
        if (!is_array($array)) {
            return false;
        }
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = arrayCoding($value, $inCharset, $outCharset);
            } else {
                $value = iconv($inCharset, $outCharset, $value);
            }
        }
        return $array;
    }
}

if (!function_exists('getWeekByDate')) {
    /**
     * 根据日期返回该日期是星期几
     *
     * @param null $date
     * @param $text string
     * @return string
     */
    function getWeekByDate($date = NULL, $text = '')
    {
        $map = [1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日'];
        $key = date('N');
        if ($date) {
            $key = date('N', strtotime($date));
        }
        return $text ? (string) $text . $map[$key] : $map[$key];
    }
}

if (!function_exists('getDateTip')) {

    /**
     * 根据时间戳返回短语描述
     *
     * @param $date
     * @return mixed|string
     */
    function getDateTip($date = null)
    {
        $map = ['-1' => '昨天', '0' => '今天', '1' => '明天'];
        if (date('Y', $date) <= 1970 || !$date) {
            $date = time();
        }
        $rs = strtotime(date('Ymd', $date)) - strtotime(date('Ymd', time()));
        $rs = $rs / 86400;
        if (array_key_exists($rs, $map)) {
            $tip = $map[$rs];
        } else {
            if ($rs > 0) {
                $tip = $rs . '天后';
            } else {
                $tip = abs($rs) . '天前';
            }
        }
        return $tip;
    }
}


if (!function_exists('mobile_encode')) {
    /**
     * 快捷调用手机号加密
     *
     * @param string $phone
     * @return mixed
     */
    function mobile_encode($phone = '')
    {
        return call_user_func([__NAMESPACE__ . '\app\common\StringHandle', 'mobile_encode'], $phone);
    }
}

if (!function_exists('mobile_decode')) {
    /**
     * 快捷调用手机号解密
     *
     * @param string $phone
     * @return mixed
     */
    function mobile_decode($phone = '')
    {
        return call_user_func([__NAMESPACE__ . '\app\haoyi\model\Role', 'getPhone'], $phone);
    }
}

if (!function_exists('commonFunc')) {
    /**
     * 公共过滤函数
     *
     * @param string $data
     * @return mixed
     */
    function commonTrim($data = '')
    {
        $result = NULL;
        if (!is_array($data)) {
            $result = trim($data, ' ,，、\t');
        } else {
            foreach ($data as $key => $val) {
                $result[$key] = commonTrim($val);
            }
        }
        return $result;
    }
}

if (!function_exists('trimInput')) {
    /**
     * 框架input封装trim过滤
     *
     * @param string $key
     * @param null $default
     * @param string $filter
     * @return mixed
     */
    function trimInput($key = '', $default = null, $filter = '')
    {
        $temp = call_user_func_array('input', [$key, $default, $filter]);
        $result = commonTrim($temp);
        return $result;
    }
}

