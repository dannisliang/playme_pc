<?php
/**
 * 公共帮助类库文件
 * author huhong
 * date 2016-05-04
 */
class Utility {
    private $CI;
    
    public function __construct() {
        $this->CI = & get_instance();
    }
    
    //计算数据开始行数
    public function get_offset($page , $pagesize)
    {
        if($page == 0){
            $page = 1;
        }
        $offset = ($page - 1) * $pagesize;
        return $offset;
    }
    
    /**
     * 分页样式
     * $all_page：    总页数
     * $display_page：当前显示页数
     * $pagesize：    每页条数
     * $url：         分页跳转链接
     */
    public function pagination($all_page , $display_page , $pagesize , $url)
    {
        //每边默认显示1个页码按钮
        $page_button = 2;
        //初始默认第一页
        if($display_page == 0){
            $display_page = 1;
        }
        //将所有页码按钮代码编入数组，页码作为key
        for($i = 1 ; $i <= $page_button ; $i++){
            //左侧（之前）页码按钮
            if(($display_page - $i) > 0){
                $left_page = $display_page - $i;
                $page_button_array[$left_page] = '<a href="'.$url.'?page='.$left_page.'">'.$left_page.'</a>';
            }
            //右侧（之后）页码按钮
            if(($display_page + $i) <= $all_page){
                $right_page = $display_page + $i;
                $page_button_array[$right_page] = '<a href="'.$url.'?page='.$right_page.'">'.$right_page.'</a>';
            }
        }
        //当前页码按钮
        $page_button_array[$display_page] = '<a class="active" href="'.$url.'?page='.$display_page.'">'.$display_page.'</a>';
        //将数组拼接成字符串
        ksort($page_button_array , 1);
        foreach ($page_button_array as $k => $v){
            $page_button_str .= $v;
        }
        //首页上一页 按钮
        if($display_page != 1){
            $start_page = '<a class="biaoqian" href="'.$url.'?page=1">&nbsp;首页</a>';
            $befor_page = '<a class="biaoqian" href="'.$url.'?page='.($display_page - 1).'">&nbsp;上一页</a>';
        }
        // 尾页 下一页 按钮
        if($display_page != $all_page){
            $next_page = '<a class="biaoqian" href="'.$url.'?page='.($display_page + 1).'">&nbsp;下一页</a>';
            $end_page   = '<a class="biaoqian" href="'.$url.'?page='.$all_page.'">&nbsp;末页</a>';
        }
        //拼接分页代码
        $page_link = $start_page.$befor_page.$page_button_str.$next_page.$end_page;
        return $page_link;
    }
    
    /**
     * rsa加密获取sign
     */
    public function sha1_sign($params,$token_key)
    {
        $params['salt'] = $this->CI->passport->get('bl_salt');
        return sha1($token_key.$params['service_name'].$params['timestamp'].$params['salt'].$params['sn'].$params['channelId']);
    }
    
    //访问外部地址（POST方式）
    function post($url, $post_data = array(),$header = array()) {
        if (is_array($post_data)) {
            $qry_str = http_build_query($post_data);
        } else {
            $qry_str = $post_data;
        }
        if (!$header) {
            $header = array();
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '15');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
        $content = trim(curl_exec($ch));
        curl_close($ch);
        return $content;
    }
    
    //get 方式
    public function get($url, $fields = array()) {
        if (is_array($fields)) {
            $qry_str = http_build_query($fields);
        } else {
            $qry_str = $fields;
        }
        if (trim($qry_str) != '') {
            $url = $url . '?' . $qry_str;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '100');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = trim(curl_exec($ch));
        curl_close($ch);
        return $content;
    }
    
    /**
     * 获取用户基础信息
     */
    public function get_user_info($uuid,$fields = '')
    {
        $this->CI->load->library('user_lib');
        $user_info  = $this->CI->user_lib->get_userinfo($uuid);
        if (!$user_info) {
            log_message('error', "get_user_info:用户信息获取失败;".$this->CI->input->ip_address().";uuid:".$uuid.";执行时间".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_OK);
            return false;
        }
        if ($fields) {
            return $user_info[$fields];
        }
        return $user_info;
    }
    
    //是否为手机号
    function is_mobile($val) {
        if (!preg_match('/^(13|14|15|17|18)\d{9}$/', $val)) {
            return false;
        }
        return true;
    }
    
    /**
     * 校验参数
     * @param array $params
     * @param string $sign
     * @return string
     */
    public function check_sign($params, $sign)
    {
        $get_sign = $this->get_sign($params);
        if (ENVIRONMENT != 'development') {
            if ($get_sign != $sign) {
                log_message('error', 'sign_err:'.$this->CI->input->ip_address().','.$get_sign.',签名错误');
                $this->CI->error_->set_error(Err_Code::ERR_PARAM_SIGN);
                $this->CI->output_json_return();
            }
        }
        return true;
    }
    
    /**
     * 获取参数校验值
     * @param array $params
     * @return string 校验值
     */
    public function get_sign($params)
    {
        foreach ($params as $key => $val) {
            if ($key == "sign" || ($val === "") || $key == 'sign_key' || $key == 'sign_recive_type') {
                continue;
            }
            $para[$key] = $params[$key];
        }
        ksort($para);
        $arg = '';
        foreach ($para as $k=>$v) {
            $arg .= $k.'='.$v.'&';
        }
        $sign_key = $this->CI->passport->get('sign_key');
        $arg .= 'key='.$sign_key;
        return md5($arg);
    }
    
    /**
     * 游戏币变更历史记录
     */
    public function blcoin_change_his($fields)
    {
        $this->CI->load->library('user_lib');
        return $this->CI->user_lib->blcoin_his($fields);
    }
    
    /**
     * 校验session
     * @return boolean
     */
    public function check_user_login()
    {
        // 获取用户信息、判断token是否有效
        if ($_SESSION['uuid']) {
            $u_info = $this->get_user_info($_SESSION['uuid']);
            $res    = $this->CI->check_token($u_info['uuid'],$u_info['channel']);
            if (!$res) {
                unset($_SESSION['uuid']);
                return false;
            }
            return $_SESSION['uuid'];
        }
        return false;
    }
    
    /**
     * 校验url是否包含弹窗参数
     */
    public function check_url($url)
    {
        $params_['host']    = $_SERVER['HTTP_HOST'];
        $params_['url']     = trim(urldecode($_SERVER['REQUEST_URI']),"/");
        $params_['query']   = urldecode($_SERVER['QUERY_STRING']);
        $url_arr    = explode("?", trim($params_['url'],"/"));
        $same   = 0;
        if (!$url_arr[0] && $url == 'index/index') {
            $same   = 1;
        }
        
        if ($url_arr[0] == $url) {
            $same   = 1;
        }
        $new_url    = $params_['url'];
        if ($same == 1) {
            $url_arr   = explode("?", $params_['url']);
            if ($url_arr[1]) {
                $query_arr  = explode("&", $params_['query']);
                foreach($query_arr as $k=>$v) {
                    $arr = explode("=", $v);
                    if ($arr[0] == 'show_message') {
                        $redirec = 1;
                        continue;
                    } elseif($arr[0] == 'window_type') {
                        $redirec = 1;
                        continue;
                    }
                    $new_arr[$arr[0]]   = $arr[1];
                }
                if ($redirec) {
                    if (!$new_arr) {
                        $new_url    = $url_arr[0];
                    } else {
                        $new_url    = $url_arr[0]."?".http_build_query($new_arr);
                    }
                    redirect(base_url().$new_url);exit;
                } else {
                    $new_url    = $url_arr[0];
                }
            }
        }
        return $new_url;
    }
}

