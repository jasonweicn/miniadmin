<?php
namespace backend\Controller;

use Mini\Base\{Action, Session};
use backend\Model\{ResponseResult, ResponseListData};

/**
 * 菜单管理
 */
class Menu extends Action
{

    /**
     * 初始化
     */
    function _init()
    {
        Session::start();
        if (Session::is_set('admin_id') && Session::is_set('admin_nickname')) {
            $this->view->assign('title', '菜单管理 - ' . APP_NAME);
            $this->view->assign('admin_nickname', Session::get('admin_nickname'));
            $this->view->_layout->setLayout('default');
        } else {
            header('location:login');
            die();
        }
    }

    /**
     * 菜单管理（列表）
     */
    function indexAction()
    {
        $this->view->display();
    }

    /**
     * 菜单管理列表数据接口
     *
     * @return \backend\Model\ResponseListData
     */
    function listAction()
    {
        $menu = new \backend\Model\Menu();
        $page = isset($_GET['page']) && preg_match('/^\d+$/', $_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) && preg_match('/^\d+$/', $_GET['limit']) ? intval($_GET['limit']) : PAGE_SIZE;
        $where = '';

        $count = $menu->getCount($where);
        $count = $count === false ? 0 : $count;
        $data = $menu->getList($page, $limit, $where);

        return new ResponseListData(0, '', $count, $data);
    }
    
    /**
     * 新增
     */
    function addAction()
    {
        $menu = new \backend\Model\Menu();
        $topMenuList = $menu->getTopLevelMenu();
        $this->assign('topMenuList', $topMenuList);

        $this->view->_layout->setLayout('iframe');
        $this->view->display();
    }

    /**
     * 新增菜单数据保存接口
     *
     * @return \backend\Model\ResponseResult
     */
    function saveAction()
    {
        $post = $this->params->_post;

        if (! isset($post['menu_name']) || empty($post['menu_name'])) {
            return new ResponseResult(0, '请填写菜单名称', 'E01');
        }
        if ($this->params->checkInject($post['menu_name'])) {
            return new ResponseResult(0, '菜单名称包含有不被允许的字符', 'E02');
        }
        if (! isset($post['pid'])) {
            return new ResponseResult(0, '请选择父级', 'E03');
        }
        if (! preg_match('/^\d+$/', $post['pid'])) {
            return new ResponseResult(0, '父级数据格式不正确', 'E04');
        }
        $route = '';
        if (isset($post['route']) && ! empty($post['route'])) {
            if (substr($post['route'], 0, 1) != '/') {
                return new ResponseResult(0, '路由请以“/”开头', 'E05');
            }
            $route = $post['route'];
        }

        $data = [
            'menu_name' => trim($post['menu_name']),
            'pid' => $post['pid'],
            'route' => $route
        ];

        $menu = new \backend\Model\Menu();

        $res = $menu->addMenu($data);
        if ($res) {
            return new ResponseResult(1, '新增菜单成功');
        }

        return new ResponseResult(0, '请求异常', 'E99');
    }
    
    /**
     * 更新
     * 
     * @return \backend\Model\ResponseResult
     */
    function updateAction()
    {
        $post = $this->params->_post;
        
        if (! isset($post['id'])) {
            return new ResponseResult(0, '未找到要修改的菜单ID', 'E01');
        }
        if (! preg_match('/^\d+$/', $post['id'])) {
            return new ResponseResult(0, '菜单ID格式不正确', 'E02');
        }
        if (! isset($post['menu_name']) || empty($post['menu_name'])) {
            return new ResponseResult(0, '请填写菜单名称', 'E03');
        }
        if ($this->params->checkInject($post['menu_name'])) {
            return new ResponseResult(0, '菜单名称包含有不被允许的字符', 'E04');
        }
        if (! isset($post['pid'])) {
            return new ResponseResult(0, '请选择父级', 'E05');
        }
        if (! preg_match('/^\d+$/', $post['pid'])) {
            return new ResponseResult(0, '父级数据格式不正确', 'E06');
        }
        $route = '';
        if (isset($post['route']) && ! empty($post['route'])) {
            if (substr($post['route'], 0, 1) != '/') {
                return new ResponseResult(0, '路由请以“/”开头', 'E07');
            }
            $route = $post['route'];
        }
        if (! isset($post['sort'])) {
            return new ResponseResult(0, '请填写排序序号', 'E08');
        }
        if (! preg_match('/^\d+$/', $post['sort'])) {
            return new ResponseResult(0, '排序序号格式不正确', 'E09');
        }
        
        $menu = new \backend\Model\Menu();
        
        if ($post['pid'] != 0) {
            $parentMenuData = $menu->getDetail($post['pid']);
            if (! $parentMenuData) {
                return new ResponseResult(0, '父级菜单不存在', 'E10');
            }
            
            $childrenMenuCount = $menu->getCount('pid='.$post['id']);
            if ($childrenMenuCount) {
                return new ResponseResult(0, '当前菜单下存在子菜单，只能作为一级菜单使用', 'E11');
            }
        }
        
        $data = [
            'menu_name' => trim($post['menu_name']),
            'pid' => $post['pid'],
            'route' => $route,
            'sort' => $post['sort'],
            'update_time' => date('Y-m-d H:i:s', time())
        ];
        
        $res = $menu->update($post['id'], $data);
        
        if ($res) {
            return new ResponseResult(1, '数据保存成功');
        }
        
        return new ResponseResult(0, '请求异常', 'E99');
    }
    
    /**
     * 禁用（逻辑删除）
     *
     * @return \backend\Model\ResponseResult
     */
    function disableAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            $menu = new \backend\Model\Menu();
            $menuDetail = $menu->getDetail($_GET['id']);
            if ($menuDetail['protected'] == 1) {
                return new ResponseResult(0, '这是受保护的系统默认菜单，不可禁用！');
            }
            $res = $menu->disable($_GET['id']);
            if ($res) {
                return new ResponseResult(1, '禁用成功');
            } else {
                return new ResponseResult(0, '禁用操作未执行成功');
            }
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }
    
    /**
     * 启用
     *
     * @return \backend\Model\ResponseResult
     */
    function enableAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            $menu = new \backend\Model\Menu();
            $res = $menu->enable($_GET['id']);
            if ($res) {
                return new ResponseResult(1, '启用成功');
            } else {
                return new ResponseResult(0, '启用操作未执行成功');
            }
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }

    /**
     * 删除
     *
     * @return \backend\Model\ResponseResult
     */
    function delAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            $menu = new \backend\Model\Menu();
            $menuDetail = $menu->getDetail($_GET['id']);
            if (empty($menuDetail)) {
                return new ResponseResult(0, '未找到您要删除的数据');
            }
            if ($menuDetail['protected'] == 1) {
                return new ResponseResult(0, '这是受保护的系统默认菜单，不可删除！');
            }
            
            // 校验子菜单
            $subMenuNums = $menu->getCount('pid=' . $menuDetail['id']);
            if ($subMenuNums > 0) {
                return new ResponseResult(0, '此菜单下存在子菜单项，暂时无法删除！');
            }
            
            // 校验关联角色
            $purviewObj = new \backend\Model\Purview();
            $purviewData = $purviewObj->getPurviewByMenuId($menuDetail['id']);
            if (! empty($purviewData)) {
                return new ResponseResult(0, '此菜单存在关联的角色，暂时无法删除！');
            }
            
            $res = $menu->del($_GET['id']);
            if ($res) {
                return new ResponseResult(1, '删除成功');
            } else {
                return new ResponseResult(0, '删除操作未执行成功');
            }
        }

        return new ResponseResult(0, '缺少必要的参数', 'E99');
    }

    /**
     * 详情
     *
     * @return \backend\Model\ResponseResult
     */
    function detailAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {

            $menu = new \backend\Model\Menu();
            $menuDetail = $menu->getDetail($_GET['id']);
            if ($menuDetail['pid'] != 0) {
                $parentMenuDetail = $menu->getDetail($menuDetail['pid']);
                if ($parentMenuDetail) {
                    $menuDetail['parent_menu_name'] = $parentMenuDetail['menu_name'];
                }
            } else {
                $menuDetail['parent_menu_name'] = '无';
            }
            
            $this->assign('detail', $menuDetail);
            $this->view->_layout->setLayout('iframe');
            $this->view->display();
        }

        return new ResponseResult(0, '缺少必要的参数');
    }

    /**
     * 编辑
     *
     * @return \backend\Model\ResponseResult
     */
    function editAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {

            $menu = new \backend\Model\Menu();
            $menuDetail = $menu->getDetail($_GET['id']);
            
            if ($menuDetail['protected'] == 1) {
                return new ResponseResult(0, '这是受保护的系统默认菜单，不可删除！');
            }
            
            // 获取一级菜单
            $topMenuList = $menu->getTopLevelMenu();
            
            if ($topMenuList) {
                foreach ($topMenuList as $key => $val) {
                    
                    // 从一级菜单数据中排除当前菜单
                    if ($val['id'] == $_GET['id']) {
                        unset($topMenuList[$key]);
                        continue;
                    }
                    
                    // 判断选中状态
                    if ($val['id'] == $menuDetail['pid']) {
                        $topMenuList[$key]['selected'] = 1;
                    } else {
                        $topMenuList[$key]['selected'] = 0;
                    }
                }
            }
            
            $this->assign('detail', $menuDetail);
            $this->assign('topMenuList', $topMenuList);
            
            $this->view->_layout->setLayout('iframe');
            $this->view->display();
        }

        return new ResponseResult(0, '缺少必要的参数');
    }
}
