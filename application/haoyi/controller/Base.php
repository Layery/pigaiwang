<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/7/30
 * Time: 19:18
 */

namespace app\haoyi\controller;

use app\haoyi\model\Menu;
use think\Controller;
use think\Db;
use think\facade\Session;
use think\facade\Hook;
use think\facade\Request;

class Base extends Controller
{

    public $_user;

    public $isSuperAdmin = false;

    /*
     * 访问不存在的控制器重定向到首页
     */
    public function _empty()
    {
        return redirect('haoyi/pigai/worklist');
    }
    /*
     * 构造方法
     */
    public function initialize()
    {
        $data = Session::get('userinfo');
        if (empty($data)) {
            $this->redirect('auth/login');
        }
        $this->_user = (object) $data;
        // 后台公共模板
        $this->assign('admin_layout', config('admin_layout'));
        $roleID = $this->_user->role_id;
        // 左侧菜单列表
        $sidebarList = Menu::getSidebarList($roleID);
        $this->assign('sidebar_list', $sidebarList);
        $currMenu = strtolower($this->request->controller()) . '-'. strtolower($this->request->action());
        $this->assign('curr_menu', $currMenu);
        // 临时屏蔽权限校验
        #Hook::add('action_begin','app\\haoyi\\behavior\\AuthChecker');
    }


    /**
     *
     *  * 封装ajax返回数据,status = Y 或 N
     *
     * @param Y or n $status
     * @param string $msg
     * @param null $data
     * @return \think\response\Json
     */
    public function setAjaxReturn($status, $msg = '', $data = null)
    {
        $return = [
            'status' => strtoupper($status),
            'msg' => ''
        ];
        if (is_array($msg)) {
            $return['data'] = $msg;
        } else {
            $return['msg'] = $msg;
        }
        if ($data) $return['data'] = $data;
        return json($return);
    }

}