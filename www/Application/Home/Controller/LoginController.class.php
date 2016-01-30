<?php
namespace Home\Controller;
class LoginController extends FrontController {
    public function login() {
        if ($this->checkConsoleLogin()){
            $this->redirect('main/index');
        }
        $this->display('Main:login');
    }

    /**
    * 用户登陆操作
    */
    public function loginPost(){

        if($this->post){
            $user_db = M('User');
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $verify_code = $_POST['verify'];
/*            if(!$this->validTR(true)) {
                alert('非法数据','Login/login');
                exit();
            }*/
            $verify = new \Think\Verify();
            if(!$verify->check($verify_code)){
                alert('验证码错误','Login/login');
                exit;
            }

            if (empty($_POST['username']) || empty($_POST['password'])){
                alert('请填写用户名及密码','Login/login');
                exit();
            }


            $user = $user_db->where(array('username'=>$username,'password'=>$password,$type=>1))->find();
            if($user) {
                $this->user = $user;
                $expiry = 0;
                $this->setAuthCookie($expiry);
                //$u_admin->where(array('mid'=>$this->user['mid']))->data(array('lastlogin'=>time()))->save();
                alert('登录成功','Main/index');
                //$this->success('登录成功',U('main/index'));
                exit();
            } else {
                alert('用户名或密码错误','',2);
                exit();
            }
            alert('用户名或密码错误','Login/login');
        }
    }

    /**
     * 会员登出
     */
    public function logout() {
        $this->disAuthCookie();
        echo '<script type="text/javascript">top.location.href="'.U('Login/login').'";</script>';
    }

    /**
     * 验证码
     */
    public function verify() {
        $Verify = new \Think\Verify();  
        $Verify->fontSize = 18;  
        $Verify->length   = 4;  
        $Verify->useNoise = true;  
        $Verify->codeSet = '0123456789';  
        $Verify->imageW = 130;  
        $Verify->imageH = 50;  
        //$Verify->expire = 600;  
        $Verify->entry();
    }

    public function _empty() {
        $this->redirect('Login/login');
        exit;
    }
}