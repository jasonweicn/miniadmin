<?php
namespace backend\Controller;

use Mini\Base\Action;
use Mini\Base\Session;
use backend\Model\ResponseResult;
use backend\Model\ResponseListData;

/**
 * 角色管理
 */
class Role extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        if (Session::is_set('admin_id') && Session::is_set('admin_nickname') ) {
            $this->view->assign('title', '角色管理 - ' . APP_NAME);
            $this->view->assign('admin_nickname', Session::get('admin_nickname'));
            $this->view->_layout->setLayout('default');
        } else {
            header('location:login');
            die();
        }
    }

    /**
     * 我的账号
     */
    function profileAction()
    {
        $this->view->display();
    }
    
    /**
     * 保存账号数据
     * 
     * @return \backend\Model\ResponseResult
     */
    function profilesaveAction()
    {
        if ($_POST) {
            //dump($_POST);die();
            $adminuser = new \backend\Model\Adminuser();
            if (! $adminuser->checkPassword(Session::get('admin_username'), $_POST['password'])) {
                return new ResponseResult(0, '旧密码输入错误', 'E01');
            }
            
            $res = $adminuser->saveProfile(Session::get('admin_username'), $_POST);
            if ($res) {
                return new ResponseResult(1, '数据保存成功');
            }
        }
        
        die();
    }

    /**
     * 角色管理（列表）
     */
    function listAction()
    {
        $this->view->display();
    }
    
    /**
     * 角色管理列表数据接口
     * 
     * @return \backend\Model\ResponseListData
     */
    function listdataAction()
    {
        $role = new \backend\Model\Role();
        $page = isset($_GET['page']) && preg_match('/^\d+$/', $_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) && preg_match('/^\d+$/', $_GET['limit']) ? intval($_GET['limit']) : PAGE_SIZE;
        $where = '';
        if (isset($_GET['searchfield']) && in_array($_GET['searchfield'], array('id', 'role_name'))) {
            if ($_GET['searchfield'] == 'id') {
                if (isset($_GET['keyword']) && preg_match('/^\d+$/', $_GET['keyword'])) {
                    $where = '`' . $_GET['searchfield'] . '`=' . $_GET['keyword'];
                }
            } else {
                if (! empty($_GET['keyword']) && ! $this->params->checkInject($_GET['keyword'])) {
                    $where = '`' . $_GET['searchfield'] . '`="' . $_GET['keyword'] . '"';
                }
            }
        }
        
        $count = $role->getCount($where);
        $count = $count === false ? 0 : $count;
        $data = $role->getList($page, $limit, $where);
        
        return new ResponseListData(0, '', $count, $data);
    }
    
    function addAction()
    {
        $this->view->_layout->setLayout('iframe');
        $this->view->display();
    }
    
    /**
     * 新增账号数据保存接口
     * 
     * @return \backend\Model\ResponseResult
     */
    function addsaveAction()
    {
        $post = $this->params->_post;
        
        if (! isset($post['role_name']) || empty($post['role_name'])) {
            return new ResponseResult(0, '请填写角色名称', 'E01');
        }
        if ($this->params->checkInject($post['role_name'])) {
            return new ResponseResult(0, '角色名称包含有不被允许的字符', 'E02');
        }
        
        $data = array(
            'role_name' => trim($post['role_name'])
        );
        
        $role = new \backend\Model\Role();
        
        // 检查角色名称是否重复
        $roleData = $role->getRole('role_name', $data['role_name']);
        if (isset($roleData['role_name']) && $roleData['role_name'] == $data['role_name']) {
            return new ResponseResult(0, '角色名称已存在', 'E03');
        }
        
        $res = $role->addRole($data);
        if ($res) {
            return new ResponseResult(1, '新增角色成功');
        }
        
        return new ResponseResult(0, '请求异常', 'E99');
    }
    
    /**
     * 删除角色
     * 
     * @return \backend\Model\ResponseResult
     */
    function delAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            if ($_GET['id'] == 1) {
                return new ResponseResult(0, '这是系统默认角色，不可删除！');
            }
            $role = new \backend\Model\Role();
            $adminuserCount = $role->getAdminuserCountByRole($_GET['id']);
            if ($adminuserCount > 0) {
                return new ResponseResult(0, '有 ' . $adminuserCount . ' 个关联此角色的用户，暂时无法删除！');
            }
            $res = $role->del($_GET['id']);
            if ($res) {
                return new ResponseResult(1, '删除成功');
            }
        }
        
        return new ResponseResult(0, '缺少必要的参数', 'E99');
    }
    
    /**
     * 恢复账号
     * 
     * @return \backend\Model\ResponseResult
     */
    function recoverAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            if ($_GET['id'] == 1) {
                return new ResponseResult(0, '超级管理员为系统默认账号，不可进行此操作！');
            }
            $adminuser = new \backend\Model\Adminuser();
            $res = $adminuser->recover($_GET['id']);
            if ($res) {
                return new ResponseResult(1, '恢复成功');
            }
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }
    
    /**
     * 详情
     * 
     * @return \backend\Model\ResponseResult
     */
    function detailAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            
            $role = new \backend\Model\Role();
            $res = $role->getDetail($_GET['id']);
            $this->assign('detail', $res);
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
            
            $role = new \backend\Model\Role();
            $res = $role->getDetail($_GET['id']);
            $this->assign('detail', $res);
            $this->view->_layout->setLayout('iframe');
            $this->view->display();
            
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }
    
    /**
     * 更新
     * 
     * @return \backend\Model\ResponseResult
     */
    function updateAction()
    {
        $post = $this->params->_post;
        
        if (! isset($post['role_name']) || empty($post['role_name'])) {
            return new ResponseResult(0, '角色名称为必填项');
        }
        if ($this->params->checkInject($post['role_name'])) {
            return new ResponseResult(0, '角色名称包含有不被允许的字符');
        }
        
        $role = new \backend\Model\Role();
        
        $res = $role->update($post['id'], $post);
        //dump($res);die();
        if ($res) {
            return new ResponseResult(1, '数据保存成功');
        }
        
        return new ResponseResult(0, '请求异常', 'E01');
        
        die();
    }
    
    function relationAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            
            $role = new \backend\Model\Role();
            $res = $role->getRelationData($_GET['id']);
            //dump($res);die();
            $this->assign('relation_data', json_encode($res));
            $this->assign('role_id', $_GET['id']);
            $this->view->_layout->setLayout('iframe');
            $this->view->display();
            
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }
    
    /**
     * 保存关联用户数据
     * 
     * @return \backend\Model\ResponseResult
     */
    function relationsaveAction()
    {
        $post = $this->params->_post;
        //dump($post);die();
        if (! isset($post['role_id']) || empty($post['role_id'])) {
            return new ResponseResult(0, '缺少必要的参数', 'E01');
        }
        if (! preg_match('/^\d+$/', $post['role_id'])) {
            return new ResponseResult(0, '您提交的数据格式不正确', 'E02');
        }
        
        $adminuserIds = array();
        if (isset($post['relation_data']) && ! empty ($post['relation_data'])) {
            foreach ($post['relation_data'] as $val) {
                if (! preg_match('/^\d+$/', $val['value'])) {
                    return new ResponseResult(0, '您提交的数据格式不正确', 'E04');
                }
                $adminuserIds[] = $val['value'];
            }
        }
        
        $role = new \backend\Model\Role();
        $res = $role->updateRoleData($post['role_id'], $adminuserIds);
        if ($res) {
            return new ResponseResult(1, '角色关联成功');
        }
        return new ResponseResult(0, '请求异常', 'E99');
    }
}
