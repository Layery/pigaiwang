<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/9/6
 * Time: 11:23
 */

namespace app\haoyi\controller;

use app\common\Common;
use app\common\StringHandle;
use app\haoyi\model\User as UserModel;
use think\Db;
use think\facade\Config;
use think\Session;


class PiGai extends \app\haoyi\controller\Base
{

    public function userList(UserModel $user)
    {
        $post = trimInput('post.') ? trimInput('post.') : trimInput('get.');
        if ($post) {
            $userList = $user->getUserList($post);
        } else {
            $userList = $user->getUserList();
        }
        if (!$post) {
            $searchParams = ['status' => 'all', 'keywords' => ''];
        } else {
            $searchParams = $post;
        }
        $this->assign('search_params', $searchParams);
        $this->assign('user_list', $userList);
        return $this->fetch();
    }

    public function setWork()
    {
        return $this->fetch();
    }

    public function workList()
    {
        $params = trimInput('get.') ? trimInput('get.') : trimInput('post.');
        if (empty($params['jumpDate'])) {
            $params['jumpDate'] = date('Y年m月d日', time());
            $jumpDate = date('Y-m-d', time());
            $params['afterJumpDate'] = date('Y-m-d', strtotime("$jumpDate +1 day"));
            $params['beforeJumpDate'] = date('Y-m-d', strtotime("$jumpDate -1 day"));
        } else {
            $jumpDate = $params['jumpDate'];
            if (date('Y', strtotime($jumpDate)) == 1970) {
                $jumpDate = date('Y-m-d', time());
            }
            $params['jumpDate'] = date('Y年m月d日', strtotime($jumpDate));
            $params['afterJumpDate'] = date('Y-m-d', strtotime("$jumpDate +1 day"));
            $params['beforeJumpDate'] = date('Y-m-d', strtotime("$jumpDate -1 day"));
        }
        $params['week'] = getWeekByDate($jumpDate, '周');
        $params['dateTip'] = getDateTip(strtotime($jumpDate));
        $sql = "select * from hy_zuoye where 1 = 1";
        $db = Db::table('hy_zuoye');
        if ($this->_user->role_id == 2) { // 学生只能看自己的作业列表
            $sql .= ' and insert_user = '. $this->_user->id;
        }
        $sql .= ' order by add_time desc';
        $list = $db->query($sql);
        foreach ($list as $key => $row)
        {
            $list[$key]['content'] = strip_tags($list[$key]['content']);
            $list[$key]['content'] = str_replace('&nbsp;', '', $list[$key]['content']);
            $list[$key]['intro'] = mb_substr($list[$key]['content'], 0, 10);
        }
        $page = trimInput('get.page', 1);
        $listRows = Config::get('paginate.list_rows') ? Config::get('paginate.list_rows') : 10;
        $this->assign('list', $list);
        $this->assign('params', $params);
        return $this->fetch();
    }

    public function edit()
    {
        $id = trimInput('get.id');
        $status = Common::quickGetData('hy_zuoye', ['id' => $id], ['title', 'content', 'tips', 'add_time', 'insert_user'], true);
        if (empty($status['title'])) {
            $this->error('非法操作, 请检查您的作业ID');
        }
        // 读取预设答案
        $sql = "select title, content, tips from hy_zuoye where is_answer = 1 and title = '" . $status['title'] . "' order by add_time desc limit 1";
        $answer = Db::table('hy_zuoye')->query($sql);
        if (!empty($answer[0])) {
            $answer = $answer[0];
        } else {
            $answer = [];
        }
        if (!empty($answer)) {
            $regArr = explode('，', $answer['tips']);
            $replaceArr = [];
            foreach ($regArr as $reg) {
                $replaceArr[] = "<span class='reg_succ'>". $reg . "</span>";
            }
            $status['content'] = str_replace($regArr, $replaceArr, $status['content']);
        }
        preg_match('/reg_succ/', $status['content'], $m);
        $this->assign('msg', empty($m) ? '这篇作业中没有找到预设文本哦' : '');
        $this->assign('zuoye', $status);
        return $this->fetch();
    }

    public function view()
    {
        $currMenu = strtolower($this->request->controller() . '-view');
        $this->assign('curr_menu', $currMenu);
        $id = trimInput('get.id');
        $status = Common::quickGetData('hy_zuoye', ['id' => $id], ['title', 'content', 'tips', 'add_time', 'insert_user'], true);
        if (empty($status['title'])) {
            $this->error('非法操作, 请检查您的作业ID');
        }
        $this->assign('zuoye', $status);
        return $this->fetch();
    }

    public function create()
    {
        $currMenu = strtolower($this->request->controller() . '-create');
        $this->assign('curr_menu', $currMenu);
        if ($post = trimInput('post.')) {
            if ($this->_user->role_id == 1) {
                $rule = [
                    'title' => 'require',
                    'tips' => 'require'
                ];
                $msg = [
                    'title.require' => '请填写作业标题',
                    'tips.require' => '请设置预设提示文字'
                ];
            } else {
                $rule = [
                    'title' => 'require',
                    'cnt' => 'require',
                ];
                $msg = [
                    'title.require' => '请填写作业标题',
                    'cnt.require' => '请填写作业内容',
                ];
            }
            $msg = $this->validate($post, $rule, $msg);
            if (true !== $msg) {
                return $this->setAjaxReturn('n', $msg);
            }
            $title = $post['title'];
            $content = $post['cnt'];
            if ($this->_user->role_id == 1) { // jiaoshi
                $check = Common::quickGetData('hy_zuoye', ['title' => $title], 'id', true);
                if ($check['id']) {
                    return $this->setAjaxReturn('n', '已经布置过相同标题的作业, 请重新设置作业标题');
                }
                $tips = str_replace([' ', '　'], '', $post['tips']);
                $tips = trim($tips, '，');
                $insert = [
                    'title' => $title,
                    'content' => $content ? $content : '',
                    'tips' => $tips ? $tips : '',
                    'add_time' => time(),
                    'insert_user' => $this->_user->id,
                    'insert_user_name' => $this->_user->nickname,
                    'is_answer' => 1  // 只有老师上传的是答案
                ];
            } else { // student
                $insert = [
                    'title' => $title,
                    'content' => $content,
                    'tips' => '',
                    'add_time' => time(),
                    'insert_user' => $this->_user->id,
                    'insert_user_name' => $this->_user->nickname,
                    'is_answer' => 0  // student can not upload answer;
                ];
            }
            $insertID = Db::table('hy_zuoye')->insertGetId($insert);
            if ($insertID) {
                return $this->setAjaxReturn(1, '保存成功');
            } else {
                return $this->setAjaxReturn(0, '保存失败');
            }
        }
        return $this->fetch();
    }

    public function delete()
    {
        $id = trimInput('get.id');
        $rs = Db::table('hy_zuoye')->delete($id);
        return $this->redirect('/pigaiwang/public/index.php/haoyi/pigai/worklist');
    }

    public function changePassword()
    {
        if ($post = $this->request->post()) {

        }
        return $this->fetch();
    }





}
