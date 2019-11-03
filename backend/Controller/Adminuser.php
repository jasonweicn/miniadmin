<?php
namespace backend\Controller;

use Mini\Base\Action;
use Mini\Base\Session;
use backend\Model\ResponseResult;
use backend\Model\ResponseListData;

/**
 * 用户管理
 */
class Adminuser extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        if (Session::is_set('admin_id') && Session::is_set('admin_nickname') ) {
            $this->view->assign('title', '用户管理 - ' . APP_NAME);
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
     * 用户管理（列表）
     */
    function listAction()
    {
        $this->view->display();
    }
    
    /**
     * 用户管理列表数据接口
     * 
     * @return \backend\Model\ResponseListData
     */
    function listdataAction()
    {
        $adminuser = new \backend\Model\Adminuser();
        $page = isset($_GET['page']) && preg_match('/^\d+$/', $_GET['page']) ? $_GET['page'] : 1;
        $limit = isset($_GET['limit']) && preg_match('/^\d+$/', $_GET['limit']) ? $_GET['limit'] : PAGE_SIZE;
        $count = $adminuser->getCount();
        $count = $count === false ? 0 : $count;
        $data = $adminuser->getList($page, $limit);
        
        return new ResponseListData(0, '', $count, $data);
    }
    
    /**
     * 删除（逻辑删除）用户
     * 
     * @return \backend\Model\ResponseResult
     */
    function delAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            if ($_GET['id'] == 1) {
                return new ResponseResult(0, '超级管理员为系统默认账号，不可删除！');
            }
            $adminuser = new \backend\Model\Adminuser();
            $res = $adminuser->del($_GET['id']);
            if ($res) {
                return new ResponseResult(1, '删除成功');
            }
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }
    
    function detailAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            
            $adminuser = new \backend\Model\Adminuser();
            $res = $adminuser->getDetail($_GET['id']);
            $this->assign('detail', $res);
            $this->view->_layout->setLayout('iframe');
            $this->view->display();
            
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }
    
    function editAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            
            $adminuser = new \backend\Model\Adminuser();
            $res = $adminuser->getDetail($_GET['id']);
            $this->assign('detail', $res);
            $this->view->_layout->setLayout('iframe');
            $this->view->display();
            
        }
        
        return new ResponseResult(0, '缺少必要的参数');
    }
    
    function updateAction()
    {
        $post = $this->params->_post;
        
        if (! isset($post['nickname']) || empty($post['nickname'])) {
            return new ResponseResult(0, '昵称为必填项');
        }
        if ($this->params->checkInject($post['nickname'])) {
            return new ResponseResult(0, '昵称包含有不被允许的字符');
        }
        
        $adminuser = new \backend\Model\Adminuser();
        
        $res = $adminuser->update($post['id'], $post);
        //dump($res);die();
        if ($res) {
            return new ResponseResult(1, '数据保存成功');
        }
        
        return new ResponseResult(0, '请求异常', 'E01');
        
        die();
    }
}
