<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/8/6
 * Time: 10:58
 */
namespace app\haoyi\controller;

use app\common\Common;
use app\common\controller\Base;
use app\haoyi\model\Role as RoleModel;
use think\Db;

class Role extends Base
{

    public function index(RoleModel $role)
    {
        $data = $role->getRoleList();
        $this->assign('role_list', $data);
        return $this->fetch();
    }

    public function add(RoleModel $role)
    {
        if ($this->request->post()) {
            $params = [
                'name' => escapeInput('post.role_name'),
                'description' => escapeInput('post.role_desc'),
                'insert_user' => $this->_user->id
            ];
            $result = $role->createRole($params);
            exit($result);
        }
        return json("请填写选项后提交");
    }

    public function edit(RoleModel $role)
    {
        $return = false;
        if (!input('post.id')) {
            return $this->setAjaxReturn('n', '请先点击选择角色条目');
        }
        if ($params = $this->request->post()) {
            $params['op_user'] = $this->_user->id;
            $return = $role->editRole($params);
        }
        return $this->setAjaxReturn($return ? 'y' : 'n');
    }

    public function delete(RoleModel $role)
    {
        if (!input('post.id')) {
            exit('请先点击选择角色条目');
        }
        if ($post = $this->request->post()) {
            $post['uid'] = $this->_user->id;
            $msg = $role->deleteRole($post);
            exit($msg);
        }
        exit('删除失败');
    }

    public function saveMenus(RoleModel $role)
    {
        if ($post = $this->request->post()) {
            if (empty($post['roleId'])) return $this->setAjaxReturn('n', '请选择要操作的角色');
            if (empty($post['menuList']))  return $this->setAjaxReturn('n', '请至少选择一项权限');
            $menuList = [];
            foreach ($post['menuList'] as $value) {
                $temp = explode('|', $value);
                $menuList[] = $temp[0];
//                if ($temp[1] == 0) { // 顶级菜单不找子集
//                    continue;
//                }
                $value = $temp[0]; // 当前选中的菜单id
                $rs = Db::table('hy_menu')->where(['status' => 1, 'p_id' => (int) $value, 'level' => 3])->column('id');
                if (empty($rs)) continue;
                foreach ($rs as $val) {
                    if (!in_array($val, $menuList)) {
                        $menuList[] = $val;
                    }
                }
            }
            $post['menuList'] = $menuList;
            $rs = $role->updateMenus($post);
            return $this->setAjaxReturn($rs ? 'Y' : 'N');
        }
    }
}