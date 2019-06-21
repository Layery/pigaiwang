<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/8/2
 * Time: 17:11
 */

namespace app\haoyi\model;

use app\common\Common;
use app\haoyi\controller\PiGai;
use think\facade\Cache;
use think\Db;
use think\facade\Session;
use think\File;
use think\Model;
use app\common\Tree;
use think\facade\Config;
use app\common\FileCache;

class Menu extends Model
{
    // 设置当前模型对应的完整数据表名称
    public $table = 'hy_menu';


    /**
     * roleid = 1 教师, 2= 学生
     *
     * @param $roleId
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getSidebarList($roleId)
    {
        if (empty($roleId)) $roleId = 2;
        if ($roleId == 1) { // 教师
            $menuList = [
                ['id' => 4, 'name' => '系统管理', 'p_id' => 0, 'controller' => '', 'action' => ''],
                ['id' => 10, 'name' => '修改密码', 'p_id' => 4, 'controller' => 'pigai', 'action' => 'changepassword'],
                ['id' => 8, 'name' => '学生管理', 'p_id' => 0, 'controller' => '', 'action' => ''],
                ['id' => 9, 'name' => '学生列表', 'p_id' => 8, 'controller' => 'pigai', 'action' => 'userlist'],
                ['id' => 5, 'name' => '作业管理', 'p_id' => 0, 'controller' => '', 'action' => ''],
                ['id' => 7, 'name' => '作业列表', 'p_id' => 5, 'controller' => 'pigai', 'action' => 'worklist'],
//                ['id' => 11, 'name' => '布置作业', 'p_id' => 5, 'controller' => 'pigai', 'action' => 'setwork'],
            ];
        } else { // 学生
            $menuList = [
                ['id' => 5, 'name' => '作业管理', 'p_id' => 0, 'controller' => '', 'action' => ''],
                ['id' => 7, 'name' => '作业列表', 'p_id' => 5, 'controller' => 'pigai', 'action' => 'worklist'],
                ['id' => 6, 'name' => '写作业', 'p_id' => 5, 'controller' => 'pigai', 'action' => 'create'],
            ];
        }

        $list = Tree::toLayer($menuList);
        return $list;
    }


}