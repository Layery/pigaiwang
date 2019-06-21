<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/8/23
 * Time: 14:33
 */
namespace app\haoyi\controller;

use app\common\Common;
use app\common\controller\Base;
use app\common\Tree;
use app\haoyi\model\Menu as MenuModel;
use think\Db;

class Menu extends Base
{

    public function index(MenuModel $menu)
    {
        $list = $menu->getMenuList();
        $params = [
            'menu_list' => $list,
            'menu_pid' => Tree::toLayer($list)
        ];
        $this->assign('params', $params);
        return $this->fetch();
    }


    public function deleteMenu(MenuModel $menu)
    {
        if ($input = $this->request->get()) {
            $id = (int) $input['id'];
            $rs = Db::table('hy_menu')->delete($id);
            return $this->setAjaxReturn($rs ? 'y' : 'n');
        }
        return $this->setAjaxReturn('n');
    }

    public function saveUpdate(MenuModel $menu)
    {
        $post = input('post.');
        $rule = [
            'name' => 'require',
            'weight' => 'require|number',
            'status' => 'require',
            'level' => 'require|number',
            'p_id' => 'require',
        ];
        if (trim($post['level']) != 1) {
            $rule['module'] = 'require';
            $rule['controller'] = 'require';
            $rule['action'] = 'require';
            $rule['url'] = 'require';
        }
        $msg = [
            'name.require' => '请填写菜单名',
            'weight.require' => '请填写权重',
            'weight.number' => '权重需是数字',
            'status.require' => '请选择是否启用',
            'level.require' => '请选择级别',
            'level.number' => '级别需是数字',
            'p_id.require' => '请选择上级菜单',
            'module.require' => '请填写module名称',
            'controller.require' => '请填写controller名称',
            'action.require' => '请填写action名称',
            'url.require' => '请填写url地址',
        ];
        $msg = $this->validate($post, $rule, $msg);
        if ($msg !== true) {
            return $this->setAjaxReturn('n', $msg);
        }
        $rs = $menu->saveMenu($post);
        return $this->setAjaxReturn($rs ? 'y' : 'n', '操作'. $rs ? '成功' : '失败');
    }

    public function get(MenuModel $menu)
    {
        $roleId = (int) input('post.roleId', 0);
        $result = $menu->getMenuTree($roleId);
        exit(json_encode($result));
    }
}