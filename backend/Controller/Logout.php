<?php
namespace backend\Controller;

use Mini\Base\Action;
use Mini\Base\Session;

class Logout extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        $this->view->title = 'Mini Admin';
    }
    
    /**
     * 默认动作
     */
    function indexAction()
    {
        Session::set('admin_id', null);
        Session::destroy();
        header('location:/');
        die();
    }
}
