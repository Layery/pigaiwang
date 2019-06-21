<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/7/31
 * Time: 14:30
 */

namespace app\haoyi\controller;

use app\common\Common;
use app\haoyi\model\User as UserModel;
use think\Db;
use think\Controller;
use app\common\FileCache;
use think\facade\Session;
use app\haoyi\model\Role;
use app\haoyi\model\Menu;

class Auth extends Controller
{

    public function login()
    {
        if ($post = input('post.')) {
            $return = ['status' => 0, 'msg' => '', 'data' => []];
            $msg = [];
            $loginName = $post['user_name'];
            $userInfo = Db::table('hy_admin_users')->where('username', $loginName)->find();
            Session::set('userinfo', $userInfo);
            $loadUrl = '/pigaiwang/public/index.php/haoyi/pigai/worklist';
            return json([
                'status' => 200,
                'msg' => 'ok',
                'data' => [
                    'jump_url' => $loadUrl
                ]
            ]);
        }
        return $this->fetch();
    }


    public function logout()
    {
        session(null);
        $this->redirect('auth/login');
    }


    public function changePassword(UserModel $user)
    {
        return $this->fetch();
    }
}