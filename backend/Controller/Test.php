<?php
namespace backend\Controller;

use Mini\Base\{Action, Session};
use backend\Model\{ResponseResult, ResponseListData};

/**
 * 测试
 */
class Test extends Action
{

    /**
     * 初始化
     */
    function _init()
    {
        Session::start();
        if (Session::is_set('admin_id') && Session::is_set('admin_nickname')) {
            $this->view->assign('title', '测试 - ' . APP_NAME);
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
    
    function aAction()
    {
        $this->view->display();
    }
    
    function bAction()
    {
        $this->view->display();
    }
}
