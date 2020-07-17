<?php
namespace backend\Model;

use Mini\Base\{Model, Session};

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
            $res = $this->table('ma_role')->where($where)->order(['id' => 'ASC'])->limit($offset, $limit)->select();
        } else {
            $res = $this->table('ma_role')->order(['id' => 'ASC'])->limit($offset, $limit)->select();
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
        $data = [];
        $data['role_name'] = $roleInfo['role_name'];
        $data['update_time'] = date('Y-m-d H:i:s');
        
        $res = $this->table('ma_role')->data($data)->where('id=' . $id)->save();
        
        return $res;
    }
    
    /**
     * 获取全部角色数据
     * 
     * @return array
     */
    public function getAllRole()
    {
        $this->useDb('default');
        $res = $this->table('ma_role')->select();
        
        return $res;
    }
    
    /**
     * 通过角色ID获取关联用户数据
     * 
     * @param int $role_id
     * @return array
     */
    public function getRelationData($role_id)
    {
        $this->useDb('default');
        $adminuserData = $this->table('ma_adminuser')->select();
        $relData = $this->table('ma_adminuser_role')->field('adminuser_id')->where('role_id=' . $role_id)->select();
        $data = [];
        if ($adminuserData) {
            foreach ($adminuserData as $val) {
                $data['l'][] = [
                    'value' => $val['id'],
                    'title' => $val['username'],
                    'disabled' => ($val['id'] == 1 && $role_id == 1) ? true : false,
                ];
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
    
    /**
     * 更新角色关联用户的数据
     * 
     * @param int $role_id
     * @param array $adminuserIds
     * @return boolean
     */
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
            $data = [];
            foreach ($adminuserIds as $adminuser_id) {
                $data[] = ['adminuser_id' => $adminuser_id, 'role_id' => $role_id];
            }
            $res = $this->table('ma_adminuser_role')->data($data)->add();
            if (! $res) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 更新权限数据
     * 
     * @param int $role_id
     * @param array $menuIds
     * @return boolean
     */
    public function updatePurviewData($role_id, $menuIds)
    {
        $this->useDb('default');
        
        // 提取已有角色权限数据
        $curPurviewData = $this->table('ma_role_purview')->where('`role_id`=' . $role_id)->select();
        if ($curPurviewData) {
            foreach ($curPurviewData as $val) {
                $k = array_search($val['menu_id'], $menuIds);
                if ($k === false) {
                    // 删除（取消）权限
                    $this->table('ma_role_purview')->where('`id`=' . $val['id'])->delete();
                } else {
                    unset($menuIds[$k]);
                }
            }
        }
        
        // 写入新的权限
        if (! empty($menuIds)) {
            $data = [];
            foreach ($menuIds as $menu_id) {
                $data[] = ['menu_id' => $menu_id, 'role_id' => $role_id];
            }
            $res = $this->table('ma_role_purview')->data($data)->add();
            if (! $res) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 获取权限数据
     * 
     * @param int $role_id
     * @return array
     */
    public function getPurviewMenuIds($role_id)
    {
        $this->useDb('default');
        $res = $this->table('ma_role_purview')->field('menu_id')->where('role_id=' . $role_id)->select();
        $data = [];
        if ($res) {
            foreach ($res as $val) {
                $data[] = $val['menu_id'];
            }
        }
        
        return $data;
    }
    
    /**
     * 获取树形列表选中状态
     * 
     * @param array $menuData
     * @param array $purviewMenuIds
     * @return boolean
     */
    public function getPurviewStatus($menuData, $purviewMenuIds)
    {
        foreach ($menuData as $key => $val) {
            if (in_array($val['id'], $purviewMenuIds)) {
                $menuData[$key]['checked'] = true;
            } else {
                $menuData[$key]['checked'] = false;
            }
            if (isset($val['children']) && count($val['children']) > 0) {
                unset($menuData[$key]['checked']);
                $menuData[$key]['spread'] = true;
                $menuData[$key]['children'] = $this->getPurviewStatus($val['children'], $purviewMenuIds);
            } else {
                unset($menuData[$key]['children']);
            }
        }
        
        return $menuData;
    }
    
    /**
     * 从树形数组中解析提取所有主键ID
     * 
     * @param array $data
     * @return boolean|array
     */
    public function parseIds($data)
    {
        $idList = [];
        foreach ($data as $key => $val) {
            if (! isset($val['id'])) {
                return false;
            }
            $idList[] = $val['id'];
            if (isset($val['children']) && count($val['children']) > 0) {
                $idList = array_merge($idList, $this->parseIds($val['children']));
            }
        }
        
        return $idList;
    }
    
    public function getMenuIdsByRoleId($roid_id)
    {
        
    }
}
