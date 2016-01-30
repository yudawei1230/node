<?php
namespace Home\Controller;
use Think\Controller;
 //父类
class FrontController extends Controller {
    /**
     * 当前用户信息
     *
     * @access protected
     */
    protected $user = FALSE;
    protected $user_info = FALSE;

        /**
     * 当前用户的ip地址
     */
    protected $ip = '';

    protected $ajax = false;
    protected $post = false;

    protected $timestamp = 0;
    protected $root_path;

    protected $upload_dir;
    protected $site_cfg;
    protected $other_script = '';
    /**
     * 系统初始化方法
     *
     * @access protected
     */
    protected function _initialize() {
        header("Content-type:text/html;charset=utf-8");
        Load('extend');

        $this->ajax = $this->IS_AJAX;
        $this->post = strtolower($_SERVER['REQUEST_METHOD']) == 'post';

        $this->ip = get_client_ip();

        $this->timestamp = time();
        $this->root_path=dirname($_SERVER['PHP_SELF']);
        $this->root_path = ($this->root_path == '\\' || $this->root_path == '/') ? '': $this->root_path;
        define('__ROOT_PATH__',$this->root_path);
        C('TMPL_PARSE_STRING.__ROOT_PATH__',__ROOT_PATH__);

        $this->site_cfg = F('site_cfg');
        $this->assign('site_cfg',$this->site_cfg);

        define('__TPL_PATH__','/Application/Home/View');
        C('TMPL_PARSE_STRING.__TPL_PATH__',__TPL_PATH__);

        $this->upload_dir = $this->root_path .'/upload/';
        define('__UPLOAD_PATH__',$this->upload_dir);
        C('TMPL_PARSE_STRING.__UPLOAD_PATH__',__UPLOAD_PATH__);
       
        // 如果当前用户验证身份cookie有效则获取相用户信息
        $_SESSION['auth'] = isset($_SESSION['auth']) ? $_SESSION['auth'] : '';
        if ( $_SESSION['auth'] != '' && $this->validAuthCookie() ) {
            C('formtoken_extra',$this->user['mid'].$this->user['password']);
        }

        if(method_exists($this,'_init')){
            $this->_init();
        }
     }

    /**
     * 验证网站来路
     * @param bool $coerce 是否强制验证默认为false
     */
    protected function validReferer($coerce = false) {
        $referer = $_SERVER['HTTP_REFERER'];
        if( empty($referer) && !$coerce ){
            return true;
        }
        $referer = parse_url($referer);
        return $referer['host'] == $_SERVER['HTTP_HOST'];
    }

    /**
     * 验证来路及表单
     *
     * @param bool $coerce 是否强制验证来路
     */
    protected function validTR($coerce = false) {
        return $this->validToken() AND $this->validReferer($coerce);
    }


    protected function validToken() {
        // 如果开启了Token验证
        if( C('TOKEN_ON') ) {
            $token = formtoken();
            return $_REQUEST[C('TOKEN_NAME')] != $token ? FALSE : TRUE;
        }
        return true;
    }

    protected function setAuthCookie($expire=0,$type="s") {
		$auth = @serialize($this->user);
		$encrypt_result = $this->authcode($auth,'ENCODE',C('auth_key').$_SERVER['HTTP_USER_AGENT']);
		$encrypt_result = str_replace('+','JIAHAO',base64_encode($encrypt_result));
        $type=="c" ? cookie('auth',$encrypt_result,$expire) : $_SESSION['auth'] = $encrypt_result;
    }

    protected function disAuthCookie($type="s") {
        ($type=="c") ? cookie('auth',NULL) : $_SESSION['auth']=NULL;
    }

    protected function validAuthCookie($type="s") {
		$auth = ($type=="c") ? urldecode(cookie('auth')) : urldecode($_SESSION['auth']);
        $decrypt_result = $this->authcode(base64_decode(str_replace('JIAHAO','+',$auth)),'DECODE',C('auth_key').$_SERVER['HTTP_USER_AGENT']);
		if($decrypt_result){
			$this->user = @unserialize($decrypt_result);
            // if($this->user) {
            //     $this->user_info = M('MemberInfo')->where(array('uid'=>$this->user['uid']))->find();
            // }
    		return true;
		}
		return false;
    }

    /**
     * 空操作方法
     *
     * @access public
     */
    public function _empty() {
        $this->header_status('404 Not Found');    
    }

    public function header_status($status){
       // 'cgi', 'cgi-fcgi'
       if (substr(php_sapi_name(), 0, 3) == 'cgi')
       header('Status: '.$status, TRUE);
       else
       header($_SERVER['SERVER_PROTOCOL'].' '.$status);
    }

    /**
     * 获取当前的URL地址
     */
    protected function getCurrentUrl() {
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    /**
     * 获取带表前缀的表名
     */
    protected function table($table_name) {
        return '`'.C('db_prefix').$table_name.'`';
    }

    /**
     * 生成 script 标签
     */
    protected function loadScript($filename,$type="1") {
        $scripts = explode(',',$filename);
        $script = '';
        if($type=="1") {
            foreach ($scripts as $filename) {
                $script .= '<script src="'.__TPL_PATH__.'js/'.$filename.'.js?r='.rand().'" type="text/javascript" charset="utf-8"></script>'."\n";
            }
        }else if($type=="3"){
            foreach ($scripts as $filename) {
                $script .='<link rel="stylesheet" href="'.__TPL_PATH__.'/js/'.$filename.'.css" type="text/css" charset="utf-8">'."\n";
            }
        }else if($type == '4'){
            foreach ($scripts as $filename) {
                $script .='<script src="'.__TPL_PATH__.$filename.'" type="text/javascript" charset="utf-8"></script>'."\n";
            }
        }else{
            foreach ($scripts as $filename) {
                $script .='<link rel="stylesheet" href="'.__TPL_PATH__.'/css/'.$filename.'.css" type="text/css" charset="utf-8">'."\n";
            }
        }
        return $script;
    }

    /**
     * 输出JSON数据
     */
    protected function outJsonMsg($state,$msg,$field='',$code='') {
        $std = new stdClass();
        $std->state = $state;
        $std->msg = $msg;
        $std->field = $field;
        $std->code = $code;
        return json_encode($std);
    }

	/**
	 * authcode 加密解密
	 */
	protected function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

		$ckey_length = 4;
		$key = md5($key ? $key : C('auth_key'));
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}

    protected function getPageData($model,$listRows,$parameter,$order,$table) {
        $db_pre = C('db_prefix');
        if(!class_exists('Page')) {
            import('ORG.Util.Page');
        }
        $model = is_object($model) ? $model : M($model);
        $count = (int) $model->table($db_pre.$table)->where($parameter)->order($order)->count();
        $page = new Page($count,$listRows);
        $page->parameter = http_build_query($parameter);
        $return = array(
                'page_list' => $page->show(),
                'data_list' => (array)$model->table($db_pre.$table)->where($parameter)->order($order)->limit($page->firstRow.','.$page->listRows)->findAll(),
            );
        return $return;
    }

    protected function checkConsoleLogin() {
        return ($this->user && $this->user) ? TRUE : FALSE;

    }
}