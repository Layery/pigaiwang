<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/8/6
 * Time: 15:53
 */

namespace app\haoyi\model;

use app\common\Common;
use app\common\FileCache;
use app\common\MyCrypt;
use app\common\StringHandle;
use think\Db;
use think\Exception;
use think\facade\Session;
use think\Model;

class User extends Model
{
    protected $table = 'hy_admin_users';

    const PASS_WORD = '123456';

    const DATA_AUTH = [
        1 => '个人',
        2 => '本组',
        3 => '部门',
        4 => '医院',
        5 => '全部',
    ];

    /**
     * 导出数据
     *
     * @return array
     */
    public function getExportData()
    {
        $return = ['head' => [], 'data' => []];
        $query = self::field('username, nickname, sex, hospital_id, section_id, group_id, role_id, duties, data_auth, status, contact, remark, insert_user, insert_time');
        if (!isSuperAdmin()) {
            $query->where(['hospital_id' => Session::get('userinfo.hospital_id')]);
        }
        $list = $query->select()->toArray();
        if (!empty($list)) {
            $head = [
                'username' => '登录账号',
                'nickname' => '用户姓名',
                'sex' => '性别',
                'hospital_id' => '医院',
                'section_id' => '部门',
                'group_id' => '组别',
                'role_id' => '角色',
                'duties' => '职务',
                'data_auth' => '数据权限',
                'status' => '状态',
                'contact' => '联系方式',
                'remark' => '备注',
                'insert_user' => '创建人',
                'insert_time' => '创建时间',
            ];
            foreach ($list as &$row) {
                $row['sex'] = $row['sex'] == 1 ? '男' : '女';
                $row['hospital_id'] = Section::getNameById($row['hospital_id']);
                $row['section_id'] = Section::getNameById($row['section_id']);
                $row['group_id'] = Section::getNameById($row['group_id']);
                $row['role_id'] = Role::getRoleById($row['role_id'], 'name');
                $dataAuth = self::DATA_AUTH;
                $row['data_auth'] = $dataAuth[$row['data_auth']];
                $row['status'] = $row['status'] == 1 ? '启用' : '禁用';
                $row['insert_user'] = self::getUserById($row['insert_user'], 'username');
                $row['insert_time'] = date('Y-m-d H:i:s', $row['insert_time']);
            }
            $return = [
                'head' => $head,
                'data' => $list
            ];
        }
        return $return;
    }

    /**
     * 根据id获取user信息
     *
     * @param $id
     * @param null $field 指定返回field字段
     * @return array|string
     */
    public static function getUserById($id, $field = null)
    {
        if ($id <= 0) return '';
        $temp = '*';
        if ($field) {
            $temp = trim(strtolower($field), ',');
        }
        $data = self::table('hy_admin_users')
            ->field($temp)
            ->find($id);
        if (!empty($data)) {
            $data = $data->toArray();
            if (!empty($field) && strpos($field, ',') === false) {
                return $data[$field];
            } else {
                return $data;
            }
        } else {
            return [];
        }
    }

    /**
     * 获取用户列表
     * 多字段模糊查询 登录账号, 姓名, 角色
     *
     * @param $params array 查询条件
     * @return array|\think\Paginator
     */
    public function getUserList($params = [])
    {
        $where = $return = [];
        if (!empty($params)) {
            if (isset($params['keywords'])) {
                if (!empty($params['keywords'])) { // 多字段模糊查询
                    $where[] = ['nickname|username', 'like', '%' . $params['keywords'] . '%'];
                }
            }
        } else {
            $where = " true ";
        }

        $result = Db::table('hy_admin_users')->where($where);
        $result = $result->order('insert_time desc')
            ->paginate(null, false, [
                'query' => $params
            ]);

        if (empty($result)) return $return;

        $result->each(function ($row) {
            $temp = [
                'sex' => $row['sex'] == 1 ? '男' : '女',
                'insert_user' => self::getUserById($row['insert_user'], 'nickname'),
                'status_text' => $row['status'] == 1 ? '启用' : '已停用', // 状态文描
                'create_user' => self::getUserById($row['insert_user'], 'username'),
                'create_time' => date('Y-m-d H:i:s', $row['insert_time']),
            ];
            return $temp + $row;
        });
        return $result;
    }


    /**
     * 保存用户
     *
     * @param $params
     * @return bool|int|string
     */
    public function saveUser($params)
    {
        $return = false;
        $isUpdate = $params['id'] ? true : false;
        if (!$isUpdate) {
            $params['salt'] = StringHandle::generateSalt();
            $params['password'] = StringHandle::encrypting(self::PASS_WORD, $params['salt']);
            $params['insert_time'] = time();
        } else {
            $params['update_time'] = time();
        }
        if (empty($params['remark'])) {
            $params['remark'] = '';
        }
        Db::startTrans();
        try {
            // 保存用户表信息
            $result = (new self())->isUpdate($isUpdate)->save($params);
            // 更新操作时, 同步更新关联表, 咨询师开发人员的医院, 部门,组id
            $currUserId = $params['id'] ? $params['id'] : 0;
            if ($isUpdate && $currUserId) {
                $currHospitalId = Session::get('userinfo.hospital_id');
                $consultSet = [
                    'consult_hospital' => $params['hospital_id'] ? $params['hospital_id'] : 0,
                    'consult_section' => $params['section_id'] ? $params['section_id'] : 0,
                    'consult_group' => $params['group_id'] ? $params['group_id'] : 0,
                ];
                $developSet = [
                    'develop_hospital' => $params['hospital_id'] ? $params['hospital_id'] : 0,
                    'develop_section' => $params['section_id'] ? $params['section_id'] : 0,
                    'develop_group' => $params['group_id'] ? $params['group_id'] : 0,
                ];
                Db::table('hy_come_hospital')->where(['hospital_id' => $currHospitalId, 'consult_id' => $currUserId])->update($consultSet);
                Db::table('hy_come_hospital')->where(['hospital_id' => $currHospitalId, 'develop_id' => $currUserId])->update($developSet);
            }

            $result = true;
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            exit('保存失败请联系系统管理员');

        }
        return false !== $result ? $result : $return;
    }

    /**
     * 禁用用户
     *
     * @param $params
     * @return string|static|bool
     */
    public function disableUser($params)
    {
        $status = $params['status'] == 1 ? 0 : 1;
        return self::where('id', $params['id'])->update(['status' => $status]);
    }
}