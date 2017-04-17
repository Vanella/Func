<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/1
 * Time: 10:04
 */
class weixinLogin {

    private $appId = 'wxe90917ea3eedece6';
    private $appKey = '5beeb146788ddd9ee235b61eeef12e26';

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
        $grant_type = 'authorization_code'; //必须	授权类型，在本步骤中，此值为“authorization_code”。
        $client_id = $this->appId; //必须	申请QQ登录成功后，分配给网站的appid。
        $client_secret = $this->appKey; //必须	申请QQ登录成功后，分配给网站的appkey。
        $redirect_uri = 'http://www.lawtoutiao.com/login/qq_redirect_url';
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
        //echo $request_url;
        $response = file_get_contents($request_url);
        echo 'response:';
        var_dump($response);

        $obj=json_decode($response);
        var_dump($obj);

        $access_token=$obj->access_token;

$openid = $obj->openid;
var_dump($openid);
var_dump($access_token);


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
