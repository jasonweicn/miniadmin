<?php
namespace backend\Model;

use Mini\Base\Model;

/**
 * 菜单模型
 */
class Menu extends Model
{    
    
    /**
     * 获取详情数据
     * 
     * @param int $id
     * @return string|array
     */
    public function getDetail($id)
    {
        $this->useDb('default');
        $res = $this->table('ma_menu')->where('id=' . $id)->select('Row');
        if ($res) {
            $res['route'] = $res['route'] == '' ? '(未设置)' : $res['route'];
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
            $res = $this->table('ma_menu')->where($where)->field('COUNT(*) as num')->select('Row');
        } else {
            $res = $this->table('ma_menu')->field('COUNT(*) as num')->select('Row');
        }
        
        if ($res && isset($res['num'])){
            return $res['num'];
        }
        
        return false;
    }
    
    public function getTreeList($type = 'default')
    {
        $this->useDb('default');
        if ($type == 'default') {
            $childKeyName = 'child';
            $listData = $this->table('ma_menu')->where('disable=0')->order(['sort' => 'ASC', 'id' => 'ASC'])->select();
        } else if ($type == 'purview') {
            $childKeyName = 'children';
            $listData = $this->table('ma_menu')->field('id,pid,menu_name AS title')->where('disable=0')->order(['sort' => 'ASC', 'id' => 'ASC'])->select();
        }
        
        if (! $listData) {
            return false;
        }
        
        $treeData = $this->getTree($listData, 0, $childKeyName);
        
        return $treeData;
    }
    
    public function getTree($listData, $pid = 0, $childKeyName = 'child')
    {
        $treeData = [];
        foreach ($listData as $row) {
            if ($row['pid'] == $pid) {
                $tmp = $this->getTree($listData, $row['id'], $childKeyName);
                if ($tmp) {
                    $row[$childKeyName] = $tmp;
                } else {
                    $row[$childKeyName] = [];
                }
                $treeData[] = $row;
            }
        }
        
        return $treeData;
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
            $res = $this->table('ma_menu')->where($where)->order(['id' => 'ASC'])->limit($offset, $limit)->select();
        } else {
            $res = $this->table('ma_menu')->order(['id' => 'ASC'])->limit($offset, $limit)->select();
        }
        
        if ($res) {
            $res2 = chgArrayKey($res, 'id');
            foreach ($res as $key => $val) {
                $res[$key]['pname'] = $val['pid'] == 0 ? '(未设置)' : $res2[$val['pid']]['menu_name'];
                $res[$key]['status'] = $val['disable'] == 1 ? '禁用' : '启用';
            }
        }
        return $res;
    }
    
    /**
     * 获取一级菜单
     * 
     * @return array
     */
    public function getTopLevelMenu()
    {
        $this->useDb('default');
        $data = [];
        $res = $this->table('ma_menu')->where('`pid`=0')->order(['sort' => 'ASC', 'id' => 'ASC'])->select();
        $data = empty($res) ? $data : $res;

        return $data;
    }
    
    
    /**
     * 新增菜单
     * 
     * @param array $data
     * @return boolean|int
     */
    public function addMenu($data)
    {
        if (empty($data)) {
            return false;
        }
        
        $this->useDb('default');
        $res = $this->table('ma_menu')->data($data)->add();
        
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
        $data = array();
        foreach ($roleList as $role_id) {
            $data[] = ['adminuser_id' => $adminuser_id, 'role_id' => $role_id];
        }
        $this->useDb('default');
        $res = $this->table('ma_adminuser_role')->data($data)->add();
        
        return $res;
    }
    
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
        $res = $this->table('ma_adminuser_role')->data($data)->add();
        
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
        $data = array(
            'disable' => 1
        );
        $res = $this->table('ma_menu')->data($data)->where('id='.$id)->save();
        
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
        $res = $this->table('ma_menu')->data($data)->where('id='.$id)->save();
        
        return $res;
    }
    
    /**
     * 删除菜单
     * 
     * @param int $id
     * @return int
     */
    public function del($id)
    {
        $this->useDb('default');
        $res = $this->table('ma_menu')->where('id='.$id)->delete();
        
        return $res;
    }
    
    /**
     * 更新菜单数据
     * 
     * @param int $id
     * @param array $data
     * @return boolean|number
     */
    public function update($id, $data)
    {
        $this->useDb('default');
        $res = $this->table('ma_menu')->where('`id`=' . $id)->select('Row');
        if (! $res) {
            return false;
        }
        $res = $this->table('ma_menu')->data($data)->where('id=' . $id)->save();
        
        return $res;
    }
}
