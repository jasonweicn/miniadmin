<?php
namespace backend\Controller;

use Mini\Base\Action;
use Mini\Base\Session;

/**
 * 这是一个控制器的案例
 */
class Index extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        
    }

    /**
     * 后台主界面
     */
    function indexAction()
    {
        if (Session::is_set('admin_id') && Session::is_set('admin_nickname') ) {
            $this->view->assign('title', '控制台 - ' . APP_NAME);
            $this->view->assign('admin_nickname', Session::get('admin_nickname'));
            $this->view->_layout->setLayout('default');

            // 渲染并显示View
            $this->view->display();
        } else {
            header('location:login');
            die();
        }
    }

    /**
     * 验证码
     */
    function verifycodeAction()
    {
        $captcha = new \Mini\Captcha\Captcha();
        $captcha->setImgSize(290, 38);
        $captcha->create();
    }
}
