<?php

class sinaLogin {

    private $appKey = '';
    private $appSecret = '';
    private $redirect_uri = '';

    public function __construct($appKey, $appSecret) {
        if (isset($appKey) && isset($appSecret)) {
            $this->appKey = $appKey;
            $this->appSecret = $appSecret;
        }
    }

    /**
     * 获取code码
     * */
    public function getAuthorizationCode() {
        $response_type = 'code'; //必须	授权类型，此值固定为“code”。
        $client_id = $this->appKey; //必须	
        $redirect_uri = $this->redirect_uri;
        $state = md5(uniqid(rand(), TRUE)); 
        $request_url = "https://api.weibo.com/oauth2/authorize?response_type=" . $response_type . "&client_id=" . $client_id . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&with_offical_account=" . 1;
        header("location:$request_url");
    }
    /**
     * 获取Token-API
     * */
    public function accessToken() {
        include_once( 'saetv2.ex.class.php' );
        $client_id = $this->appKey;
        $client_secret = $this->appSecret;       
        $o = new SaeTOAuthV2($client_id, $client_secret);
        $keys['code'] = $_GET['code'];
        $keys['redirect_uri'] = $this->redirect_uri;
        $token = $o->getAccessToken($type = 'code', $keys);
        if($token==="error"){
            return 'index';
        }else{
            $access_token=$token['access_token'];
            /**
             * 获取用户信息
             * */
            $c = new SaeTClientV2($client_id, $client_secret,$access_token);
            $uid_get = $c->get_uid();
            $uid = $uid_get['uid'];
            $user_message = $c->show_user_by_id($uid); //根据ID获取用户等基本信息
            return $user_message;
        }

    }

}
