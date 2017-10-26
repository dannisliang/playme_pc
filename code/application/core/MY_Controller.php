<?php
/**
 * @author yuanlong
 */

/**
 * @property CI_Loader $load
 * @property CI_DB_active_record $db
 * @property CI_Calendar $calendar
 * @property Email $email
 * @property CI_Encrypt $encrypt
 * @property CI_Ftp $ftp
 * @property CI_Hooks $hooks
 * @property CI_Image_lib $image_lib
 * @property CI_Language $language
 * @property CI_Log $log
 * @property CI_Output $output
 * @property CI_Pagination $pagination
 * @property CI_Parser $parser
 * @property CI_Session $session
 * @property CI_Sha1 $sha1
 * @property CI_Table $table
 * @property CI_Trackback $trackback
 * @property CI_Unit_test $unit
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $agent
 * @property CI_Validation $validation
 * @property CI_Xmlrpc $xmlrpc
 * @property CI_Zip $zip
 * @property CI_Input $input
 * 
 * @property permissions_lib $permissions_lib
 */
class MY_Controller extends CI_Controller{
    const PAGESIZE  = 20;
    public $uuid;
    public $ts,$template_data  = Array();
    function __construct() {
            parent::__construct();
            $this->__add_theme_path();
            $this->ts   = time();
            $this->load->driver('cache',array( 'adapter'=>'file', 'backup'=>'memcached'));
            $this->uuid = (int)$_SESSION['uuid'];
    }

    /**
     * 压送变量到公共页面对象变量中
     * @param array $data
     */
    public function push_template_data(Array $data){
            $this->template_data = array_merge($this->template_data,$data);
    }

    /**
     * 模板
     * @param string $filename
     * @param string $return
     */
    public function view($filename,$return=FALSE){
        return $this->load->view($filename,$this->template_data,$return);
    }

    private function __add_theme_path(){
            $this->load->add_package_path( FCPATH . config_item("sys_style") . DIRECTORY_SEPARATOR );
    }	
        
    /**
     * 获取post get 数据
     */
    public function request_params($key)
    {
        if ($key == '') {
            return false;
        }
        $p = $this->input->get_post($key, true);
        if (is_array($p)) {
            return $p;
        }
        return trim($p);
    }
        
    /**
     * jason格式输出
     * @param array $data
     */
    public function output_json_return($data = array()) {
        header('Content-type: application/json;charset=utf-8');
        $code = $this->error_->get_error();
        if ($code == null) {
            $this->error_->set_error(Err_Code::ERR_OK);
            $code = $this->error_->get_error();
        }
        if(empty($data)){
           $data = new stdClass();
        }
        $new_data   = array('c' => $code, 'm' => $this->error_->error_msg(), 'data' => $data);
        echo json_encode(array('resCode' => $code,'msg'=>$this->error_->error_msg(), 'obj' => $new_data));exit;
    }
    
    /**
     * 设置session
     * @param type $data
     * @return boolean
     */
    public function set_session($data) {
        $this->session->set_userdata($data);
        $session_id = session_id();
        $expire = $this->passport->get('login_expire');
        delete_cookie('session_id');
        $cookie = array(
            'name'   => 'session_id',
            'value'  => $session_id,
            'expire' => $expire
        );
        set_cookie($cookie);
        return true;
    }
    
    /**
     * 获取session
     * @param type $key
     * @return type
     */
    public function get_session($key) {
        return $this->session->userdata($key);
    }

    /**
     * 清除session
     */
    public function destroy_session() {
        delete_cookie('session_id');
        $this->session->sess_destroy();
    }
    

    /**
     * 页面错误信息
     * @param type $url
     * @param type $window_type 窗口类型 1普通错误弹窗 2含有"前往充值"弹窗 3直接跳转
     */
    public function output_handle($url,$window_type = 1)
    {
        if ($window_type == 3) {
            redirect($url);exit;
        }
        $code   = $this->error_->get_error();
        if ($code !== '00100000') {
            $message    = $this->error_->error_msg();
            redirect($url."?show_message=".$message."&window_type=".$window_type);
        }
        redirect($url);
        exit;
    }
    
    /**
     * 校验用户是否登录
     */
    public function check_token($uuid,$channel)
    {
        $token_key  = $this->passport->get('pctoken_key');
        $key        = $uuid."_".$channel."_".$token_key;
        $token_info = $this->get_token($key);
        if (!$token_info['token']) {
            return false;
        }
        return $token_info['uuid'];
    }
    
    /**
     * 生成登录TOKEN
     */
    public function gen_login_token($uuid,$channel)
    {
        $token_key      = $this->passport->get('pctoken_key');
        $token_expire   = $this->passport->get('pctoken_expire');
        
        $login_ts                   = time();
        $key                        = $uuid."_".$channel."_".$token_key;
        $item['token']              = md5($key."_".$login_ts);
        $item['login_ts']           = $login_ts;
        $item['token_expire']       = $token_expire;
        $item['login_expire_ts']    = $login_ts + $token_expire;
        $item['uuid']               = $uuid;
        $res = $this->cache->memcached->save($key, $item, $token_expire);
        if ($res) {
            return $item['token'];
        }
        $this->error_->set_error(Err_Code::ERR_TOKEN_SET_FAIL);
    }
    
    /**
     * 获取token信息
     */
    public function get_token($key)
    {
        return $this->cache->memcached->get($key);
    }
    
    /**
     * 删除TOKEN
     */
    public function delete_token($key)
    {
        return $this->cache->memcached->delete($key);
    }
	
}

