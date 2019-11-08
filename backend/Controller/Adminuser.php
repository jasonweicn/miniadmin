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
        $page = isset($_GET['page']) && preg_match('/^\d+$/', $_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) && preg_match('/^\d+$/', $_GET['limit']) ? intval($_GET['limit']) : PAGE_SIZE;
        $where = '';
        if (isset($_GET['searchfield']) && in_array($_GET['searchfield'], array('id', 'username', 'nickname'))) {
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
        
        $count = $adminuser->getCount($where);
        $count = $count === false ? 0 : $count;
        $data = $adminuser->getList($page, $limit, $where);
        
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
        
        if (! isset($post['username']) || empty($post['username'])) {
            return new ResponseResult(0, '请填写用户名', 'E01');
        }
        if ($this->params->checkInject($post['username'])) {
            return new ResponseResult(0, '用户名包含有不被允许的字符', 'E02');
        }
        if (! isset($post['nickname']) || empty($post['nickname'])) {
            return new ResponseResult(0, '请填写昵称', 'E03');
        }
        if ($this->params->checkInject($post['nickname'])) {
            return new ResponseResult(0, '昵称包含有不被允许的字符', 'E04');
        }
        if (! isset($post['password1']) || empty($post['password1'])) {
            return new ResponseResult(0, '请填写密码', 'E05');
        }
        
        $data = array(
            'username' => trim($post['username']),
            'nickname' => trim($post['nickname']),
            'password' => trim($post['password1'])
        );
        
        $adminuser = new \backend\Model\Adminuser();
        
        // 检查用户名是否重复
        $userData = $adminuser->getProfile('username', $data['username']);
        if (isset($userData['username']) && $userData['username'] == $data['username']) {
            return new ResponseResult(0, '用户名已存在', 'E06');
        }
        
        // 检查昵称是否重复
        $userData = $adminuser->getProfile('nickname', $data['nickname']);
        if (isset($userData['nickname']) && $userData['nickname'] == $data['nickname']) {
            return new ResponseResult(0, '昵称已存在', 'E07');
        }
        
        $res = $adminuser->addProfile($data);
        if ($res) {
            return new ResponseResult(1, '新增账号成功');
        }
        
        return new ResponseResult(0, '请求异常', 'E99');
    }
    
    /**
     * 冻结（逻辑删除）账号
     * 
     * @return \backend\Model\ResponseResult
     */
    function delAction()
    {
        if (isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id'])) {
            if ($_GET['id'] == 1) {
                return new ResponseResult(0, '超级管理员为系统默认账号，不可冻结！');
            }
            $adminuser = new \backend\Model\Adminuser();
            $res = $adminuser->del($_GET['id']);
            if ($res) {
                return new ResponseResult(1, '冻结成功');
            }
        }
        
        return new ResponseResult(0, '缺少必要的参数');
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
