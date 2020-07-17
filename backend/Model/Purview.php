<?php
namespace backend\Model;

use Mini\Base\Model;

/**
 * 权限模型
 */
class Purview extends Model
{    
    public function getPurviewByAdminUserId($adminUserId)
    {
        $this->useDb('default');
        
        $roleData = $this->table('ma_adminuser_role')->field('role_id')->where('`adminuser_id`='.$adminUserId)->select();
        if (! $roleData) {
            return false;
        }
        $roleIds = [];
        foreach ($roleData as $val) {
            $roleIds[] = $val['role_id'];
        }
        
        $purviewData = $this->table('ma_role_purview')->field('menu_id')->where('`role_id` IN ('.implode(',', $roleIds).')')->select();
        
        return $purviewData;
    }

    /**
     * 通过菜单ID获取权限数据
     * 
     * @param int $menu_id
     * @return array
     */
    public function getPurviewByMenuId($menu_id)
    {
        $this->useDb('default');
        $res = $this->table('ma_role_purview')->where('`menu_id`='.$menu_id)->select();
        
        return $res;
    }
    
    /**
     * 通过角色ID删除权限数据
     * 
     * @param int $role_id
     * @return int
     */
    public function delPurviewByRoleId($role_id)
    {
        $this->useDb('default');
        $res = $this->table('ma_role_purview')->where('role_id=' . $role_id)->delete();
        
        return $res;
    }
}
