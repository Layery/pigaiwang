<?php
/**
 * 行为校验类
 *
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/8/1
 * Time: 18:24
 */
namespace app\haoyi\behavior;

use app\haoyi\model\Role;
use think\Request;
use \traits\controller\Jump;

class AuthChecker
{
    use Jump;

    public function run(Request $request)
    {
        if (!Role::checkAuth()) {
            if ($request->isAjax()) {
                return $this->setAjaxReturn('n', '您没有操作权限');
            } else {
                $this->error('您没有操作权限');
            }
        }
    }

    /**
     * 封装ajax返回数据,status = Y 或 N
     *
     * @param string Y or N $status
     * @param string|array $msg
     * @param null $data
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
        exit(json_encode($return,JSON_UNESCAPED_UNICODE));
    }

}