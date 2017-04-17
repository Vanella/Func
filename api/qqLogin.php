<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/1
 * Time: 10:04
 */
class qqLogin{
    private $appId = '101377982';
    private $appKey = 'b7756a548141703384c921a7208673db';
    public function __construct($appId, $appKey) {
        if(isset($appId) && isset($appKey)){
            $this->appId = $appId;
            $this->appKey = $appKey;
        }
    }
    /**
     * 获取code码
     * */
    public function getAuthorizationCode($href){
        $response_type = 'code';	//必须	授权类型，此值固定为“code”。
        $client_id	= $this->appId;//必须	申请QQ登录成功后，分配给应用的appid。
        $redirect_uri = 'http://m.lawtoutiao.com/login/qq_redirect_url';
        $state  = md5(uniqid(rand(),TRUE));//必须    client端的状态值。用于第三方应用防止CSRF攻击，成功授权后回调时会原样带回。请务必严格按照流程检查用户与state参数状态的绑定。
        $request_url = "https://graph.qq.com/oauth2.0/authorize?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&state=".$state;

        header("location:$request_url");
    }
    /**
     * 获取Token
     * */
    public function getAccessToken(){
        $code = $code_num;//必须	上一步返回的authorization code。如果用户成功登录并授权，则会跳转到指定的回调地址，并在URL中带上Authorization Code。例如，回调地址为www.qq.com/my.php，则跳转到：http://www.qq.com/my.php?code=520DD95263C1CFEA087******注意此code会在10分钟内过期。
        $redirect_uri = 'http://m.lawtoutiao.com/login/qq_redirect_url';
        $url_arr = array(
            'grant_type'=>'authorization_code',
            'client_id' =>$this->appId,
            'redirect_uri'=>$redirect_uri,
            'client_secret'=>$this->appKey,
            'code'=>$_GET['code'],
            'state'=>$_GET['state']
        );
        $request_url = "https://graph.qq.com/oauth2.0/token";
        $request_url = $request_url.'?'.http_build_query($url_arr);
        //echo $request_url;
        $response = file_get_contents($request_url);
        $params = array();
        parse_str($response, $params);
        $access_token = $params['access_token'];
        $open_id = $this->getOpenId($access_token);
        /**
         * 获取用户信息
         * */
        $oauth_consumer_key = $this->appId;
        $get_info_url = "https://graph.qq.com/user/get_user_info?access_token=$access_token&oauth_consumer_key=$oauth_consumer_key&openid=$open_id";
        $result =  json_decode(file_get_contents($get_info_url),true);
        $infoArr['openid'] = $open_id;
        $infoArr['nick_name'] = $result['nickname'];
        $infoArr['head_img'] = $result['figureurl_qq_1'];
        return $infoArr;
    }
    /**
     * 获取OpenID
     * */
    public function getOpenId($token){
        $request_url = "https://graph.qq.com/oauth2.0/me?access_token=".$token."";
        $response = file_get_contents($request_url);
        if(strpos($response, "callback") !== false){
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
            $user = json_decode($response,true);
            return $user['openid'];
        }
    }
}