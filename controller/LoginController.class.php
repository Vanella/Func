<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller{
    /**
    *页面登录
     */
    public function index(){
    }

    /**
     * QQ登录
     * */
    public function qq_login(){
        Vendor ( 'api.qqLogin' );
        $href = I('get.href');
        $qc = new \qqLogin();
        $qc->getAuthorizationCode($href);
    }
    /**
     * QQ登录回调
     * */
    public function qq_redirect_url(){
        Vendor ( 'api.qqLogin' );
        $state = 'test';
        $href = $_GET['href'];
        if($_GET['state'] = $state) {
            $code = $_GET['code'];
            $qc = new \qqLogin();
            $user_info = $qc->getAccessToken($code);
            $table = M('member');
            $member = $table->where(array(
                'oid' => $user_info['openid'],
                'is_delete' => 0
            ))->find();
            if($member){
                $table -> where(array(
                    'oid' => $user_info['openid'],
                    'is_delete' => 0
                ))->setField(array(
                    'last_time' => time(),
                    'last_ip' => get_client_ip()
                ));
                session('member_id',$member['id']);
                $this->redirect($href);
            }else{
                //var_dump($user_info);
                 $data['nick_name'] = $user_info['nick_name'];
                 $data['oid'] = $user_info['openid'];
                 $data['head_img'] = $user_info['head_img'];
                 $data['last_time'] = time();
                 $data['create_time'] = time();
                 $data['last_ip'] = get_client_ip();
                 $add_mem = $table->add($data);
                 if($add_mem){
                     session('member_id',$add_mem);
                     $this->redirect($href);
                 }
            }
        }
    }
        /**
     *微信扫码登录回调
     * */
    public function weichat(){
        Vendor ( 'api.qqLogin' );
        $state = 'test';
        $href = $_GET['href'];
        if($_GET['state'] = $state) {
            $code = $_GET['code'];
            $qc = new \qqLogin();
            $user_info = $qc->getAccessToken($code);
            var_dump($user_info);
            $table = M('member');
            $member = $table->where(array(
                'oid' => $user_info['openid'],
                'is_delete' => 0
            ))->find();
            if($member){
                $table -> where(array(
                    'oid' => $user_info['openid'],
                    'is_delete' => 0
                ))->setField(array(
                    'last_time' => time(),
                    'last_ip' => get_client_ip()
                ));
                session('member_id',$member['id']);
                $this->redirect($href);
            }else{
                //var_dump($user_info);
                 $data['nick_name'] = $user_info['nick_name'];
                 $data['oid'] = $user_info['openid'];
                 $data['head_img'] = $user_info['head_img'];
                 $data['last_time'] = time();
                 $data['create_time'] = time();
                 $data['last_ip'] = get_client_ip();
                 $add_mem = $table->add($data);
                 if($add_mem){
                     session('member_id',$add_mem);
                     $this->redirect($href);
                 }
            }
        }
    }
        /**
     * SINA登录
     * */
    public function sina_login(){
        Vendor ( 'api.sinaLogin' );
        $href = I('get.href');
        $qc = new \sinaLogin();
        $qc->getAuthorizationCode();
    }
    /**
     *sina登录回调
     * */
    public function sina_redirect_url(){
        Vendor ( 'api.sinaLogin' );
        //$state = 'test';
        //$href = $_GET['href'];
        //if($_GET['state'] = $state) {
            $code = $_GET['code'];
            $qc = new \sinaLogin();
            $user_info = $qc->accessToken();
            if($user_info==="index"){
                $this->redirect('/');
            }else{
            $table = M('member');
            $member = $table->where(array(
                'oid' => $user_info['openid'],
                'is_delete' => 0
            ))->find();
            if($member){
                $table -> where(array(
                    'oid' => $user_info['id'],
                    'is_delete' => 0
                ))->setField(array(
                    'last_time' => time(),
                    'last_ip' => get_client_ip()
                ));
                session('member_id',$member['id']);
                $this->redirect('/');
            }else{
                $data['nick_name'] = $user_info['screen_name'];
                $data['oid'] = $user_info['id'];
                $data['head_img'] = $user_info['profile_image_url'];
                $data['last_time'] = time();
                $data['create_time'] = time();
                $data['last_ip'] = get_client_ip();
                $add_mem = $table->add($data);
                if($add_mem){
                    session('member_id',$add_mem);
                    $this->redirect('/');
                }
            }
        }
    }
    /**
    *退出
     */
    public function logout(){
        session('member_id',null);
        $this->redirect( '/');
    }
}