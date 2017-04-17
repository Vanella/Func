<?php

class weixinLogin {

    private $appId = '';
    private $appKey = '';

    public function __construct($appId, $appKey) {
        if (isset($appId) && isset($appKey)) {
            $this->appId = $appId;
            $this->appKey = $appKey;
        }
    }

    /**
     * 获取Token
     * */
    public function getAccessToken() {
        $grant_type = 'authorization_code'; //必须	
        $client_id = $this->appId; //必须	
        $client_secret = $this->appKey; //必须	
        $redirect_uri = '';
        $url_arr = array(
            'grant_type' => 'authorization_code',
            'appid' => $this->appId,
            'redirect_uri' => $redirect_uri,
            'secret' => $this->appKey,
            'code' => $_GET['code'],
            'state' => $_GET['state']
        );
        $request_url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $request_url = $request_url . '?' . http_build_query($url_arr);
        $response = file_get_contents($request_url);
        $obj=json_decode($response);
        $access_token=$obj->access_token;
        $openid = $obj->openid;
        /**
         * 获取用户信息
         * */
        $get_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
        
        $result = json_decode(file_get_contents($get_info_url), true);
        var_dump($result);
        $infoArr['openid'] = $openid;
        $infoArr['nick_name'] = urlencode($result['nickname']);
        $infoArr['head_img'] = $result['headimgurl'];
        return $infoArr;
    }
}
