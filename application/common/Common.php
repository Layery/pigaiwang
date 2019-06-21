<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/8/10
 * Time: 18:16
 */
namespace app\common;

use think\Db;
use think\Exception;

class Common {
    /**
     * (通用)将array的每个val转换key
     */
    public static function shiftValToKey($arr = array(), $keyName) {
        $arrRes = array();
        foreach($arr as $val) {
            $arrRes[$val[$keyName]] = $val;
        }

        return $arrRes;
    }

    /**
     * 获取数组的字段s
     */
    public static function getArrayFields($arr = array(), $field = '') {
        $fields = '';
        foreach($arr as $v) {
            if(isset($v[$field])) {
                $fields .= $v[$field].',';
            }
        }
        $fields = rtrim($fields, ', ');
        return $fields;
    }

    /**
     * 二维数组根据元素去重
     */
    public static function twoArrayUnique($arr,$key){
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                //有：销毁
                unset($value[$key]);
            }
            else{
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }

    /**

     * * 封装获取表数据方法
     * $result = Common::quickGetData('hy_user', ['status' => 1], 'id, name, sex')
     *
     * @param $table
     * @param null $where  where条件
     * @param null $column 待查询字段 可以是数组或逗号拼接字符串
     * @param bool $find
     * @param bool $fetchSql // 是否返回拼接sql
     * @throws Exception
     * @return array|Exception
     */
    public static function quickGetData($table, $where = null, $column = null, $find = false, $fetchSql = false)
    {
        if (!$table) return [];
        $field = '*';
        if (!empty($column) && $column != '*') {
            $field = is_array($column) ? implode(',', $column) : $column;
        }
        $andWhere = 1;
        if ($where) $andWhere = $where;
        try {
            $result = Db::table($table)
                ->where($andWhere)
                ->field($field);
            if ($fetchSql) {
                $result->fetchSql();
            }
            if ($find == true) {
                $result = $result->find();
            } else {
                $result = $result->select();
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $result;
    }

}
