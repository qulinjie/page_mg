<?php

class ApiController extends Controller {
	public static $_apiUserSessionKey = "apiLoginUser";
	
	public function handle($params = array()) {}
	
	//判断是否是api方式访问
	public static function isApi(){
		if(defined('IN_API')){
			return true;
		}
		return false;
	}
	
	//api接口调用安全验证
	public static function securityValidate(){
	
	}
	
	//获取get/post提交的loginkey
	private static $_loginkey = NULL;
	public static function getLoginkey(){		
		if(NULL !== self::$_loginkey){
			return self::$_loginkey;
		}		
		$loginkey = NULL;
		if(self::isApi()){
			//支持get/post提交loginkey,优先支持get方式
			$loginkey = Request::get(UserController::$_loginKeyName);
			if(empty($loginkey)){
				$loginkey = Request::post(UserController::$_loginKeyName);
			}			
		}
		if(!empty($loginkey)){
			return self::$_loginkey = $loginkey;
		}
		return $loginkey;
	}
	
	//将loginkey的md5值作为session_id
	private static $_session_id = NULL;
	public static function getSessionId(){
		if(NULL !== self::$_session_id){
			return self::$_session_id;
		}
		if(true && $loginkey = self::getLoginkey()){			
			return self::$_session_id = md5($loginkey);
		}
		return NULL;
	}
	
	//设置session_id
	public static function setSessionId(){
		$session_id = self::getSessionId();
		if(self::isApi() && !empty($session_id)){
			if(session::get_id() != $session_id){
				//echo "<br>----setSessionId()----<br>";
				session::set_id(self::getSessionId());
				return true;
			}
		}
		return false;	
	}
	
	private static $_isLogin = NULL;
	public static function isLogin() {
		if(NULL !== self::$_isLogin){
			return self::$_isLogin;
		}
		$loginUser = self::getLoginUser();
		if(!empty($loginUser) && is_array($loginUser) && isset($loginUser['usercode'])){
			return self::$_isLogin  = true;
		}else{
			return self::$_isLogin = false;
		}
		return self::$_isLogin;
	}
	
	//对api方式访问的进行loginkey方式登录user
	public static function loginByLoginkey(){		
		$loginkey = self::getLoginkey();
		if(self::isApi() && !empty($loginkey) && !self::isLogin()){
			//echo "<br>---- loginByLoginkey ----<br>";			
			$data = self::model('user')->erp_login_by_loginkey(['loginkey' => $loginkey]);
			$data['code'] !== EC_OK_ERP && EC::fail($data['code'], $data['msg']);
			
			self::setLoginSession($data['data']);			
			self::$_loginUser = NULL;
			self::$_isLogin = NULL;
		}		
	}
		
	protected static function setLoginSession($loginUser){
    	if(empty($loginUser)){
    		Log::error('setLoginSession [loginUser] is empty .');
    		return false;
    	}
    	$session = self::instance('session');    	
    	if(isset($loginUser['password'])) unset( $loginUser['password'] );
    	$session->set(self::$_apiUserSessionKey, $loginUser);    	
    	return true;
    }
	
    private static $_loginUser = NULL;
    public static function getLoginUser(){
    	$loginUser = NULL;    	 
    	
    	if(NULL !== self::$_loginUser){
    		return self::$_loginUser;
    	}    	 
    	$session = self::instance('session');
    	if($session->is_set(self::$_apiUserSessionKey)){
    		$loginUser = $session->get(self::$_apiUserSessionKey);
    	}    
    	if(!$loginUser){    		
    		$loginUser = [];    		
    	}    	 
    	return self::$_loginUser = $loginUser;
    }
}