<?php
namespace backend\Controller;

use Mini\Base\Action;
use backend\Model\{ResponseResult, ResponseListData};

/**
 * 通用
 */
class Common extends Action
{

    /**
     * 加载菜单数据
     * 
     * @return \backend\Model\ResponseResult
     */
    function loadmenuAction()
    {
        $menu = new \backend\Model\Menu();
        $menuList = $menu->getTreeList();
        
        return new ResponseResult(1, '', '', $menuList);
    }
}
