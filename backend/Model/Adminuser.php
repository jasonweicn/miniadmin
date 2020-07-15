<?php
namespace backend\Model;

use Mini\Base\{Model, Session};

/**
 * 后台用户模型
 */
class Adminuser extends Model
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
        
        $userData = [];
        $encrypt_password = md5($password . $res['encrypt']);
        if ($encrypt_password == $res['password']) {
            $data = ['login_time' => date('Y-m-d H:i:s')];
            if (! $this->table('ma_adminuser')->data($data)->where('id=' . $res['id'])->save()) {
                return false;
            }
            Session::start();
            Session::set('admin_id', $res['id']);
            Session::set('admin_username', $res['username']);
            Session::set('admin_nickname', $res['nickname']);
            Session::set('admin_login_time', $res['login_time']);
            $userData = [
                'admin_id' => $res['id'],
                'admin_username' => $res['username'],
                'admin_login_time' => $res['login_time']
            ];
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
        $data = [];
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
        $res = $this->table('ma_adminuser')->where('id=' . $id)->select('Row');
        if ($res) {
            $res['status'] = $res['disable'] == 1 ? '禁用' : '启用';
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
            $res = $this->table('ma_adminuser')->where($where)->field('COUNT(*) as num')->select('Row');
        } else {
            $res = $this->table('ma_adminuser')->field('COUNT(*) as num')->select('Row');
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
            $res = $this->table('ma_adminuser')->where($where)->order(['id' => 'ASC'])->limit($offset, $limit)->select();
        } else {
            $res = $this->table('ma_adminuser')->order(['id' => 'ASC'])->limit($offset, $limit)->select();
        }
        
        if ($res) {
            foreach ($res as $key => $val) {
                $res[$key]['status'] = $val['disable'] == 1 ? '禁用' : '启用';
            }
        }
        return $res;
    }
    
    /**
     * 获取用户资料
     * 
     * @param string $search_field
     * @param string $search_value
     * @param string $field
     * @return boolean|array
     */
    public function getProfile($search_field, $search_value, $field = '*')
    {
        if (empty($search_field) || empty($search_value)) {
            return false;
        }
        
        $this->useDb('default');
        $res = $this->table('ma_adminuser')->field($field)->where('`'.$search_field.'`="' . $search_value . '"')->select('Row');
        if (! $res) {
            return false;
        }
        
        return $res;
    }
    
    /**
     * 新增账号
     * 
     * @param array $data
     * @return boolean|int
     */
    public function addProfile($data)
    {
        if (empty($data)) {
            return false;
        }
        $data['encrypt'] = getRandomString(8);
        $data['password'] = md5($data['password'] . $data['encrypt']);
        $data['create_time'] = date('Y-m-d H:i:s');
        
        $this->useDb('default');
        $res = $this->table('ma_adminuser')->data($data)->add();
        
        if ($res) {
            $db = $this->loadDb('default');
            return $db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * 新增用户关联的角色
     * 
     * @param int $id
     * @param array $roleList
     * @return int
     */
    public function addRoleData($adminuser_id, $roleList)
    {
        $data = [];
        foreach ($roleList as $role_id) {
            $data[] = ['adminuser_id' => $adminuser_id, 'role_id' => $role_id];
        }
        $this->useDb('default');
        $res = $this->table('ma_adminuser_role')->data($data)->add();
        
        return $res;
    }
    
    /**
     * 更新用户关联的角色
     * 
     * @param int $adminuser_id
     * @param array $roleList
     * @return boolean
     */
    public function updateRoleData($adminuser_id, $roleList)
    {
        $this->useDb('default');
        
        // 提取已有角色
        $curRoleData = $this->table('ma_adminuser_role')->where('`adminuser_id`=' . $adminuser_id)->select();
        if ($curRoleData) {
            foreach ($curRoleData as $val) {
                $k = array_search($val['role_id'], $roleList);
                if ($k === false) {
                    // 删除取消的角色
                    $this->table('ma_adminuser_role')->where('`id`=' . $val['id'])->delete();
                } else {
                    unset($roleList[$k]);
                }
            }
        }
        
        // 写入新的角色
        $data = [];
        foreach ($roleList as $role_id) {
            $data[] = ['adminuser_id' => $adminuser_id, 'role_id' => $role_id];
        }
        if (! empty($data)) {
            $res = $this->table('ma_adminuser_role')->data($data)->add();
        } else {
            $res = true;
        }
        
        
        return $res;
    }
    
    /**
     * 禁用（逻辑删除）
     * 
     * @param int $id
     * @return int
     */
    public function disable($id)
    {
        $this->useDb('default');
        $data = [
            'disable' => 1
        ];
        $res = $this->table('ma_adminuser')->data($data)->where('id='.$id)->save();
        
        return $res;
    }
    
    /**
     * 启用
     * 
     * @param int $id
     * @return int
     */
    public function enable($id)
    {
        $this->useDb('default');
        $data = [
            'disable' => 0
        ];
        $res = $this->table('ma_adminuser')->data($data)->where('id='.$id)->save();
        
        return $res;
    }
    
    /**
     * 删除账号
     * 
     * @param int $id
     * @return int
     */
    public function del($id)
    {
        $this->useDb('default');
        $res = $this->table('ma_adminuser')->where('id='.$id)->delete();
        
        return $res;
    }
    
    /**
     * 更新账号资料
     * 
     * @param int $id
     * @param array $profile
     * @return boolean|number
     */
    public function update($id, $profile)
    {
        //dump($profile);die();
        $this->useDb('default');
        $res = $this->table('ma_adminuser')->where('`id`=' . $id)->select('Row');
        if (! $res) {
            return false;
        }
        $data = [];
        $data['nickname'] = $profile['nickname'];
        if (isset($profile['password']) && ! empty($profile['password'])) {
            $data['encrypt'] = getRandomString(8);
            $data['password'] = md5($profile['password'] . $data['encrypt']);
        }
        $data['update_time'] = date('Y-m-d H:i:s');
        
        $res = $this->table('ma_adminuser')->data($data)->where('id=' . $id)->save();
        
        return $res;
    }
    
    /**
     * 获取角色数据
     * 
     * @param int $id
     * @return array
     */
    public function getRole($id)
    {
        $this->useDb('default');
        $res = $this->table('ma_adminuser_role')->where('`adminuser_id`=' . $id)->select();
        
        return $res;
    }
}
