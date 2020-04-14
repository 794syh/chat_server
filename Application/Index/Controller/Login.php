<?php
/**
 * 登录/注册页
 *
 * @author syh <794syh940@gmail.com>
 */

namespace app\Index\Controller;

class Login extends Action
{
    /**
     * 登录
     */
    public function index()
    {
        if (self::isLogin()) {
            echo '<script type="text/javascript">location="/";</script>';
            exit;
        }

        $this->assign("login_time", time());
        return $this->fetch();
    }

    /**
     * 注册
     */
    public function register()
    {
        if (self::isLogin()) {
            echo '<script type="text/javascript">location="/";</script>';
            exit;
        }

        $this->assign("register_time", time());
        return $this->fetch();
    }
}