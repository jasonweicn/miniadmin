<?php
namespace backend\Controller;

use Mini\Base\{Action, Session};
use backend\Model\Adminuser;

class Login extends Action
{
    /**
     * 登录页
     */
    function indexAction()
    {
        $errcode = $this->params->getParam('errcode');
        if (empty($errcode)) {
            $errcode = 0;
        }
        
        $this->assign('errcode', $errcode);
        $this->view->display();
    }
    
    /**
     * 登录校验
     */
    function checkAction()
    {
        $post = $this->params->getPost();
        
        if (isset($post['username']) && isset($post['password'])) {
            if (empty($post['username']) || empty($post['password'])) {
                header('location:../login?errcode=1');
                die();
            }
            if ($this->params->checkInject($post['username'])) {
                header('location:../login?errcode=2');
                die();
            }
            $captcha = new \Mini\Captcha\Captcha();
            if (! isset($post['verifycode']) || $captcha->check($post['verifycode']) === false) {
                header('location:../login?errcode=3');
                die();
            }
        } else {
            header('location:../login?errcode=1');
            die();
        }
        $adminuser = new Adminuser();
        $res = $adminuser->login($post['username'], $post['password']);
        //dump($res);die();
        if ($res === false) {
            header('location:../login?errcode=1');
            die();
        }
        
        header('location:../');
        die();
    }
}
