<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/11/5
 * Time: 17:41
 */

namespace app\haoyi\controller;

use app\common\Common;
use app\common\controller\Base;
use app\haoyi\model\Reception as RecepModel;
use app\haoyi\model\Order;
use think\Db;
use think\facade\Config;
use app\haoyi\model\Section;
use think\facade\Session;

class Reception extends Base
{
    public function index(RecepModel $reception)
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
        $pageConfig = [
            'jumpDate' => $jumpDate,
            'page' => $params['page'],
            'keywords' => trimInput('post.keywords') ? trimInput('post.keywords') : trimInput('get.keywords')
        ];
        $list = $reception->getReceptionList($pageConfig);
        $totalStoodUp = Db::table('hy_booking')->where(['status' => 1, 'booking_date' => strtotime(date('Ymd'))])->count();
        $config = Config::get('custom.');
        $page = trimInput('get.page', 1);
        $listRows = Config::get('paginate.list_rows') ? Config::get('paginate.list_rows') : 10;
        $this->assign('countItem', $listRows * ($page - 1)); // 翻过了countItem条数据
        $this->assign('list', $list);
        $this->assign('totalStoodUp', $totalStoodUp);
        $this->assign('params', $params);
        $this->assign('config', [
            'reception' => $config['hy_reception']['status'],
            'partType' => $config['hy_booking']['booking_part']
        ]);
        return $this->fetch();
    }


    /**
     * 保存确认到院
     *
     * @param RecepModel $reception
     * @return \think\response\Json
     */
    public function beSureComeIn(RecepModel $reception)
    {
        $result = ['status' => 'n', 'msg' => '操作失败'];
        if ($post = trimInput('post.')) {
            $rule = [
                'status' => 'require',
                'remark' => 'max:100',
            ];
            $msg = [
                'status.require' => '请选择分诊状态',
                'remark.max' => '备注不得超过100个字',

            ];
            $msg = $this->validate($post, $rule, $msg);
            if (true !== $msg) {
                return $this->setAjaxReturn('n', $msg);
            }
            if (empty($post['consult_id'])) return $this->setAjaxReturn('n', '请选择咨询师');
            $result = $reception->beSureComeIn($post);
        }
        return $this->setAjaxReturn($result['status'], $result['msg']);
    }

    /**
     * 获取客户待关联咨询师的订单列表
     *
     * @param RecepModel $reception
     * @return \think\response\Json
     */
    public function getWaitLinkOrder(RecepModel $reception)
    {
        $customerId = (int) input('post.client_id');
        if ($this->request->isAjax() && $customerId) {
            $list = $reception->getWaitLinkOrder($customerId);
            return $this->setAjaxReturn('y', $list);
        }
        return $this->setAjaxReturn('n');
    }

    /**
     * 保存关联
     *
     * @param RecepModel $reception
     * @return \think\response\Json
     */
    public function relationOrder(RecepModel $reception)
    {
        $result = ['status' => 'n', 'msg' => '操作失败'];
        if ($post = trimInput('post.')) {
            $result = $reception->relationOrder($post);
        }
        return $this->setAjaxReturn($result['status'], $result['msg']);
    }

    /**
     * 获取到院信息的详情
     *
     * @param RecepModel $reception
     */
    public function getDetailReception(RecepModel $reception)
    {
        if ($post = trimInput('post.')) {
            $id = (int) $post['id'];
            $data = $reception->getReceptionDetail($id, $post['field']);
            return $this->setAjaxReturn('y', $data);
        }
        return $this->setAjaxReturn('n');
    }

    /**
     * 编辑分诊接待
     *
     * @return \think\response\Json
     */
    public function editReception()
    {
        if ($post = trimInput('post.')) {
            $rule = [
                'status' => 'number',
                'part_type' => 'number',
                'remark' => 'max:100',
            ];
            $msg = [
                'status.number' => '请选择分诊状态',
                'part_type.number' => '请选择意向项目',
                'remark' => '备注不得超过100个字',
            ];
            $msg = $this->validate($post, $rule, $msg);
            if (true !== $msg) {
                return $this->setAjaxReturn('n', $msg);
            }
            $id = (int) $post['id'];
            if ($id) {
                unset($post['id']);
                \app\common\model\Log::addLog(44, $id, '编辑分诊接待');
                RecepModel::where('id', $id)->update($post);
            }
        }
        return $this->setAjaxReturn('y', '编辑成功');
    }

    /**
     * 爽约列表
     *
     * @param RecepModel $reception
     * @return mixed
     */
    public function stoodUp(RecepModel $reception)
    {
        $currMenu = strtolower($this->request->controller() . '-index');
        $this->assign('curr_menu', $currMenu);
        $keywords = trimInput('post.keywords') ? trimInput('post.keywords') : trimInput('get.keywords');
        $list = $reception->getStoodUpList($keywords);
        $page = trimInput('get.page', 1);
        $listRows = Config::get('paginate.list_rows') ? Config::get('paginate.list_rows') : 10;
        $this->assign('countItem', $listRows * ($page - 1)); // 翻过了countItem条数据
        $this->assign('list', $list);
        $config = Config::get('custom.');
        $this->assign('config', [
            'status' => $config['hy_reception']['status'],
        ]);
        return $this->fetch();
    }

    public function addReception(RecepModel $reception)
    {
        $currMenu = strtolower($this->request->controller() . '-index');
        $this->assign('curr_menu', $currMenu);
        $config = Config::get('custom.');
        if ($post = trimInput('post.')) {
            $rule = [
                'real_name' => 'require|max:20',
                'sex' => 'require',
                'phone_crypt' => 'require|regex:/^1[34567890]{1}\d{9}$/',
                'source' => 'number',
                'level' => 'number',
                'status' => 'number',
                'part_type' => 'number',
                'remark' => 'max:100',
            ];
            $msg = [
                'real_name.require' => '请填写客户姓名',
                'real_name.max' => '客户姓名不得超过20个字',
                'sex.require' => '请选择性别',
                'phone_crypt.require' => '请填写手机号',
                'phone_crypt.regex' => '请检查手机号格式',
                'source.number' => '请选择客户来源',
                'level.number' => '请选择客户级别',
                'status.number' => '请选择分诊状态',
                'part_type.number' => '请选择意向项目',
                'remark' => '备注不得超过100个字',
            ];
            $msg = $this->validate($post, $rule, $msg);
            if (true !== $msg) {
                return $this->setAjaxReturn('n', $msg);
            }
            $rs = $reception->saveReception($post);
            return $this->setAjaxReturn($rs['status'], $rs['msg']);
        }

        $frontData = [
            'source' => $config['hy_customer']['source'],
            'level' => $config['hy_customer']['level'],
            'reception' => $config['hy_reception']['status'],
            'partType' => $config['hy_booking']['booking_part']
        ];
        $this->assign('frontData', $frontData);
        return $this->fetch();
    }

    /**
     * 治疗记录
     *
     * @param RecepModel $reception
     * @return mixed
     */
    public function cureList(RecepModel $reception)
    {
        $searchParams = [
            'startTime' => trimInput('post.startTime') ? trimInput('post.startTime') : trimInput('get.startTime'),
            'endTime' => trimInput('post.endTime') ? trimInput('post.endTime') . ' 23:59:59' : (trimInput('get.endTime') ? trimInput('get.endTime') . '23:59:59' : ''),
            'keywords' => trimInput('post.keywords') ? trimInput('post.keywords') : trimInput('get.keywords')
        ];
        $list = $reception->getCureList($searchParams);
        $page = trimInput('get.page', 1);
        $listRows = Config::get('paginate.list_rows') ? Config::get('paginate.list_rows') : 10;
        $this->assign('doctorList', Common::quickGetData('hy_admin_users', ['status' => 1, 'hospital_id' => DOCTOR_SECTION_ID], 'id,nickname'));
        $this->assign('countItem', $listRows * ($page - 1)); // 翻过了countItem条数据
        $this->assign('config', Config::get('custom.hy_reception.status'));
        $this->assign('list', $list);
        return $this->fetch();
    }


    /**
     * ajax获取当前医院下的所有成员
     *
     * @param Section $section
     * @return \think\response\Json
     */
    public function getUserOnHospital(Section $section)
    {
        $list = [];
        $status = 'y';
        if ($this->request->isAjax()) {
            $hospitalId = Session::get('userinfo.hospital_id');
            $keywords = trimInput('post.keywords');
            if (empty(trim($keywords))) {
                return $this->setAjaxReturn('n', '请输入用户名');
            }
            $result = $section->getUserListBySectionParams($keywords, ['hospital_id' => $hospitalId], 'id,nickname');
            $list = !empty($result) ? $result : '用户不存在哦';
            $status = !empty($result) ? 'y' : 'n';
        }
        return $this->setAjaxReturn($status, $list);
    }



    /**
     * 保存划扣登记
     *
     * @param RecepModel $reception
     * @return \think\response\Json
     */
    public function saveCureLog(RecepModel $reception)
    {
        if ($post = trimInput('post.')) {
            $result = $reception->saveCureLog($post);
            return $this->setAjaxReturn($result['status'], $result['msg']);
        }
        return $this->setAjaxReturn('n', '保存失败');
    }

    /**
     * 编辑到院登记信息
     *
     * @param RecepModel $reception
     * @return \think\response\Json
     */
    public function editCureLog(RecepModel $reception)
    {
        if ($post = trimInput('post.')) {
            $result = $reception->editCureLog($post);
            return $this->setAjaxReturn($result['status'], $result['msg']);
        }
        return $this->setAjaxReturn('n', '编辑失败');
    }

    /**
     * 获取可划扣的子订单列表信息
     *
     * @param RecepModel $reception
     * @return \think\response\Json
     */
    public function getCanStrokeList(RecepModel $reception)
    {
        $list = [];
        if ($post = trimInput('post.')) {
            $orderId = $post['order_id'] + 0;
            $list = $reception->getCanStrokeList($orderId);
        }
        return $this->setAjaxReturn('y', $list);
    }


    /**
     * 现场咨询列表
     *
     * @param RecepModel $reception
     * @return mixed
     */
    public function live(RecepModel $reception)
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
        $pageConfig = [
            'jumpDate' => $jumpDate,
            'page' => $params['page'],
            'keywords' => trimInput('post.keywords') ? trimInput('post.keywords') : trimInput('get.keywords')
        ];
        $list = $reception->getLiveList($pageConfig);
        $config = Config::get('custom.');
        $page = trimInput('get.page', 1);
        $listRows = Config::get('paginate.list_rows') ? Config::get('paginate.list_rows') : 10;
        $this->assign('countItem', $listRows * ($page - 1)); // 翻过了countItem条数据
        $this->assign('list', $list);
        $this->assign('params', $params);
        $this->assign('config', [
            'source' => $config['hy_customer']['source'],
            'dealRemark' => $config['hy_reception']['deal_remark']
        ]);
        return $this->fetch();
    }

    /**
     * 编辑现场咨询
     *
     * @param RecepModel $reception
     * @return \think\response\Json
     */
    public function editLive(RecepModel $reception)
    {
        $post = trimInput('post.');
        if (isset($post['submit'])) {
            $result = $reception->editLive($post);
            return $this->setAjaxReturn($result['status'], $result['msg']);
        }
        $info = $reception->getReceptionDetail($post['id'], 'client_id,remark,consult_desc,is_deal,deal_remark');
        return $this->setAjaxReturn('y', $info);
    }

    /**
     * 到院记录
     *
     * @param RecepModel $reception
     * @return mixed
     */
    public function comeInList(RecepModel $reception)
    {
        $searchParams = [
            'status' => trimInput('post.status') ? trimInput('post.status') : trimInput('get.status'),
            'startTime' => trimInput('post.startTime') ? trimInput('post.startTime') : trimInput('get.startTime'),
            'endTime' => trimInput('post.endTime') ? trimInput('post.endTime') . ' 23:59:59' : (trimInput('get.endTime') ? trimInput('get.endTime') . '23:59:59' : ''),
            'keywords' => trimInput('post.keywords') ? trimInput('post.keywords') : trimInput('get.keywords')
        ];
        $allowStatus = Config::get('custom.hy_reception.status');
        if ($searchParams['status'] != 'all' && !array_key_exists($searchParams['status'], $allowStatus)) {
            $searchParams['status'] = 'all';
        }
        $list = $reception->getComeInList($searchParams, 'id,status,is_deal,deal_remark,client_id,hospital_id,insert_time,consult_desc,remark,consult_id,part_type,create_id');
        $page = trimInput('get.page', 1);
        $listRows = Config::get('paginate.list_rows') ? Config::get('paginate.list_rows') : 10;
        $this->assign('countItem', $listRows * ($page - 1)); // 翻过了countItem条数据
        $this->assign('config', Config::get('custom.hy_reception.status'));
        $this->assign('list', $list);
        return $this->fetch();
    }


    /**
     * 划扣登记
     *
     * @param RecepModel $reception
     * @return mixed
     */
    public function strokeList(RecepModel $reception)
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

        $limit = strtotime(date('Y-m-01'));
        if (strtotime($jumpDate) <= $limit) {
            $jumpDate = date('Y-m-01');
            $params['jumpDate'] = date('Y年m月d日', strtotime($jumpDate));
            $params['beforeJumpDate'] = date('Y-m-01');
            $params['afterJumpDate'] = date('Y-m-d', strtotime("$jumpDate +1 day"));
        }
        $params['week'] = getWeekByDate($jumpDate, '周');
        $params['dateTip'] = getDateTip(strtotime($jumpDate));
        $searchParams = [
            'startTime' => $jumpDate,
            'endTime' => $jumpDate . ' 23:59:59',
            'keywords' => trimInput('post.keywords')
        ];
        $list = $reception->getComeInList($searchParams, 'id,client_id,status,hospital_id,consult_id,insert_time');
        $page = trimInput('get.page', 1);
        $listRows = Config::get('paginate.list_rows') ? Config::get('paginate.list_rows') : 10;
        $this->assign('doctorList', Common::quickGetData('hy_admin_users', ['status' => 1, 'hospital_id' => DOCTOR_SECTION_ID], 'id,nickname'));
        $this->assign('countItem', $listRows * ($page - 1)); // 翻过了countItem条数据
        $this->assign('list', $list);
        $this->assign('params', $params);
        return $this->fetch();
    }

    /**
     * 用户订单列表(划扣页面用)
     *
     * @return \think\response\Json
     */
    public function userOrderList()
    {
        $msg_arr = array(
            'status' => false,
            'info' => '暂无订单',
            'data' => array(),
        );
        $client_id = $this->request->post('client_id', '');   //客户id
        if (empty($client_id)) {
            return json($msg_arr);
        }
        $order_info = Order::clientOrderList($client_id);
        $order_status = Order::$order_status;
        $str = '';
        foreach ($order_info as $val) {
            $str .= '<tr class="item" data-big-order="' . $val['order_id'] . '" data-client="'. $val['client_id']. '">
                        <td>' . date('Y-m-d H:i:s', $val['insert_time']) . '</td>
                        <td>
                            <div class="show-order">
                                <span class="blue order-num" data-id="' . $val['order_id'] . '">' . $val['order_id'] . '</span>
                                <div class="show-order-cont show-order-' . $val['order_id'] . '">
                                    <div class="table-wraper">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>订单号</th>
                                                <th>商品名称</th>
                                                <th style="min-width:70px">医生</th>
                                                <th style="min-width: 50px">已划扣数量/购买数量</th>
                                                <th style="min-width:70px">状态</th>
                                            </tr>
                                            </thead>
                                            <tbody class="tr-order-' . $val['order_id'] . '">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>' . $val['consult_first_name'] . '</td>
                        <td>' . $order_status[$val['status']] . '</td>
                        <td><span class="edit-btn blue pop-huakou-btn">划扣登记</span></td>
                    </tr>';
        }
        $msg_arr['status'] = true;
        $msg_arr['data'] = $str;
        return json($msg_arr);
    }
}