<?php
namespace backend\Model;

use Mini\Base\Model;
use Mini\Base\Session;

/**
 * 角色模型
 */
class Role extends Model
{    
    /**
     * 登录验证
     * 
     * @param string $username
     * @param string $password
     * @return boolean|array
     */
    public function login($username, $password)
    {
        $this->useDb('default');
        $res = $this->table('ma_adminuser')->where('`username`="' . $username . '"')->select('Row');
        
        if (! $res) {
            return false;
        }
        
        $userData = array();
        $encrypt_password = md5($password . $res['encrypt']);
        if ($encrypt_password == $res['password']) {
            $data = array('login_time' => date('Y-m-d H:i:s'));
            if (! $this->table('ma_adminuser')->data($data)->where('id=' . $res['id'])->save()) {
                return false;
            }
            Session::start();
            Session::set('admin_id', $res['id']);
            Session::set('admin_username', $res['username']);
            Session::set('admin_nickname', $res['nickname']);
            Session::set('admin_login_time', $res['login_time']);
            $userData = array(
                'admin_id' => $res['id'],
                'admin_username' => $res['username'],
                'admin_login_time' => $res['login_time']
            );
        } else {
            return false;
        }
        
        return $userData;
    }
    
    /**
     * 校验密码
     * 
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function checkPassword($username, $password)
    {
        $this->useDb('default');
        $res = $this->table('ma_adminuser')->field('username,password,encrypt')->where('`username`="' . $username . '"')->select('Row');
        if (! $res) {
            return false;
        }
        $encrypt_password = md5($password . $res['encrypt']);
        if ($encrypt_password == $res['password']) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 保存账号数据
     * 
     * @param string $username
     * @param array $profile
     * @return boolean|number
     */
    public function saveProfile($username, $profile)
    {
        $this->useDb('default');
        $res = $this->table('ma_adminuser')->where('`username`="' . $username . '"')->select('Row');
        
        if (! $res) {
            return false;
        }
        $data = array();
        $data['encrypt'] = getRandomString(8);
        $data['password'] = md5($profile['newpassword1'] . $data['encrypt']);
        $data['update_time'] = date('Y-m-d H:i:s');
        
        $res = $this->table('ma_adminuser')->data($data)->where('username="'.$username.'"')->save();
        
        return $res;
    }
    
    /**
     * 获取详情数据
     * 
     * @param int $id
     * @return string|array
     */
    public function getDetail($id)
    {
        $this->useDb('default');
        $res = $this->table('ma_role')->where('id=' . $id)->select('Row');
        if ($res) {
            $res['role_user_total'] = $this->getAdminuserCountByRole($id);
        }
        
        return $res;
    }
    
    /**
     * 统计总行数
     * 
     * @return int|boolean
     */
    public function getCount($where = null)
    {
        $this->useDb('default');
        if (isset($where) && ! empty($where)) {
            $res = $this->table('ma_role')->where($where)->field('COUNT(*) as num')->select('Row');
        } else {
            $res = $this->table('ma_role')->field('COUNT(*) as num')->select('Row');
        }
        
        if ($res && isset($res['num'])){
            return $res['num'];
        }
        
        return false;
    }
    
    /**
     * 获取数据列表
     * 
     * @param int $page
     * @return string|array
     */
    public function getList($page = 1, $limit = PAGE_SIZE, $where = null)
    {
        $page = is_int($page) ? $page : 1;
        $offset = $page == 1 ? 0 : ($page - 1) * $limit;
        $this->useDb('default');
        if (isset($where) && ! empty($where)) {
            $res = $this->table('ma_role')->where($where)->order(array('id' => 'ASC'))->limit($offset, $limit)->select();
        } else {
            $res = $this->table('ma_role')->order(array('id' => 'ASC'))->limit($offset, $limit)->select();
        }
        
        if ($res) {
            foreach ($res as $key => $val) {
                $res[$key]['role_user_total'] = $this->getAdminuserCountByRole($val['id']);
            }
        }
        
        return $res;
    }
    
    /**
     * 获取角色关联的用户数
     * 
     * @param int $role_id
     * @return int
     */
    public function getAdminuserCountByRole($role_id)
    {
        $this->useDb('default');
        $res = $this->table('ma_adminuser_role')->where('role_id=' . $role_id)->field('COUNT(*) as num')->select('Row');
        
        if ($res && isset($res['num'])){
            return $res['num'];
        }
        
        return 0;
    }
    
    /**
     * 读取角色数据
     * 
     * @param string $search_field
     * @param mixed $search_value
     * @param string $field
     * @return boolean|array
     */
    public function getRole($search_field, $search_value, $field = '*')
    {
        if (empty($search_field) || empty($search_value)) {
            return false;
        }
        
        $this->useDb('default');
        $res = $this->table('ma_role')->field($field)->where('`'.$search_field.'`="' . $search_value . '"')->select('Row');
        if (! $res) {
            return false;
        }
        
        return $res;
    }
    
    /**
     * 新增角色
     * 
     * @param array $data
     * @return boolean|int
     */
    public function addRole($data)
    {
        if (empty($data)) {
            return false;
        }
        $this->useDb('default');
        $res = $this->table('ma_role')->data($data)->add();
        
        return $res;
    }
    
    /**
     * 禁用（逻辑删除）
     * 
     * @param int $id
     * @return int
     */
    /*
    public function disable($id)
    {
        $this->useDb('default');
        $data = array(
            'disable' => 1
        );
        $res = $this->table('ma_adminuser')->data($data)->where('id='.$id)->save();
        
        return $res;
    }
    */
    
    /**
     * 启用
     * 
     * @param int $id
     * @return int
     */
    /*
    public function enable($id)
    {
        $this->useDb('default');
        $data = array(
            'disable' => 0
        );
        $res = $this->table('ma_adminuser')->data($data)->where('id='.$id)->save();
        
        return $res;
    }
    */
    
    /**
     * 删除
     * 
     * @param int $id
     * @return int
     */
    public function del($id)
    {
        $this->useDb('default');
        $res = $this->table('ma_role')->where('id='.$id)->delete();
        
        return $res;
    }
    
    /**
     * 更新角色资料
     * 
     * @param int $id
     * @param array $profile
     * @return boolean|number
     */
    public function update($id, $roleInfo)
    {
        //dump($profile);die();
        $this->useDb('default');
        $res = $this->table('ma_role')->where('`id`=' . $id)->select('Row');
        if (! $res) {
            return false;
        }
        $data = array();
        $data['role_name'] = $roleInfo['role_name'];
        $data['update_time'] = date('Y-m-d H:i:s');
        
        $res = $this->table('ma_role')->data($data)->where('id=' . $id)->save();
        
        return $res;
    }
    
    public function getAllRole()
    {
        $this->useDb('default');
        $res = $this->table('ma_role')->select();
        
        return $res;
    }
    
    public function getRelationData($role_id)
    {
        $this->useDb('default');
        $adminuserData = $this->table('ma_adminuser')->select();
        $relData = $this->table('ma_adminuser_role')->field('adminuser_id')->where('role_id=' . $role_id)->select();
        $data = array();
        if ($adminuserData) {
            foreach ($adminuserData as $val) {
                $data['l'][] = array(
                    'value' => $val['id'],
                    'title' => $val['username'],
                    'disabled' => ($val['id'] == 1 && $role_id == 1) ? true : false,
                );
            }
            if ($relData) {
                $relData = chgArrayKey($relData, 'adminuser_id');
                foreach ($adminuserData as $val) {
                    if (isset($relData[$val['id']])) {
                        $data['r'][] = $val['id'];
                    }
                }
            }
        }
        
        return $data;
    }
    
    public function updateRoleData($role_id, $adminuserIds)
    {
        $this->useDb('default');
        
        // 提取已有角色关联数据
        $curRoleData = $this->table('ma_adminuser_role')->where('`role_id`=' . $role_id)->select();
        if ($curRoleData) {
            foreach ($curRoleData as $val) {
                $k = array_search($val['adminuser_id'], $adminuserIds);
                if ($k === false) {
                    // 删除取消关联的用户
                    $this->table('ma_adminuser_role')->where('`id`=' . $val['id'])->delete();
                } else {
                    unset($adminuserIds[$k]);
                }
            }
        }
        
        // 写入新的角色关联数据
        if (! empty($adminuserIds)) {
            $data = array();
            foreach ($adminuserIds as $adminuser_id) {
                $data[] = array('adminuser_id' => $adminuser_id, 'role_id' => $role_id);
            }
            $res = $this->table('ma_adminuser_role')->data($data)->add();
            if (! $res) {
                return false;
            }
        }
        
        return true;
    }
}
