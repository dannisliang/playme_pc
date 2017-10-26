<?php
class User_lib extends Base_lib {
    public function __construct() {
        parent::__construct();
        $this->CI->load->model('user_model');
    }
    
    /**
     * 执行登录操作
     * @param type $params 登录参数
     */
    public function do_login($params)
    {
        // openapi登录
        if (ENVIRONMENT == 'production' || ENVIRONMENT == 'testing') {
            $res    = $this->login_for_passportid($params);
            if (!$res) {
                return false;
            }
        }
        $this->CI->user_model->start();
        //查询该用户是否已注册
        $_uuid = $this->chk_user_account($params['user_id']);
        if ($_uuid === false) {
            //新注册用户 插入用户表
            $data   = array(
                'U_NAME'            => $params['nickname'],
                'U_ICON'            => $params['image'],
                'U_SEX'             => 0,
                'U_BLCOIN'          => 0,
                'U_MOBILEPHONE'     => $params['mobile'],
                'U_LASTLOGINTIME'   => $this->zeit,
                'U_SN'              => $params['user_id'],
                'U_CHANNEL'         => $params['channel'],
                'U_PASSPORTID'      => $params['passport_id'],
                'STATUS'            => 0,
            );
            $_uuid = $this->CI->user_model->insert_data($data,'bl_user');
            if (!$_uuid) {
                log_message('error', "do_login:新注册用户插入用户表失败;".$this->CI->input->ip_address().";请求参数params:".  http_build_query($params).";插入数据:".json_encode($data).";执行时间".date('Y-m-d H:i:s',time()));
                $this->CI->user_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_INSERT_USER_INFO_FAIL);
                return false;
            }
            //插入用户登入表
            $data2  = array(
                'U_USERIDX'     => $_uuid,
                'U_ACCOUNTID'   => $params['user_id'],
                'U_ACCOUNTNAME' => $params['nickname'],
                'U_CHANNEL'     => $params['channel'],
                'STATUS'        => 0,
            );
            $rst = $this->CI->user_model->insert_data($data2,'bl_userlogin');
            if (!$rst) {
                log_message('error', "do_login:插入用户注册表失败;".$this->CI->input->ip_address().";请求参数params:".  http_build_query($params).";插入数据:".json_encode($data2).";执行时间".date('Y-m-d H:i:s',time()));
                $this->CI->user_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_INSERT_USERLOGIN_FAIL);
                return false;
            }
        } else {
            // 更新用户信息
            $where  = array('IDX'=>$_uuid,'status'=>0);
            $fields = array(
                'U_NAME'            => $params['nickname'],
                'U_MOBILEPHONE'     => $params['mobile'],
                'U_ICON'            => $params['image'],
                'U_LASTLOGINTIME'   => $this->zeit,
                'U_CHANNEL'         => $params['channel'],
                'U_PASSPORTID'      => $params['passport_id'],
                'U_SN'              => $params['sn'],
            );
            $rst = $this->CI->user_model->update_data($fields,$where,'bl_user');
            if (!$rst) {
                log_message('error', "do_login:更新用户信息表失败;".$this->CI->input->ip_address().";请求参数params:".  http_build_query($params).";更新字段:".json_encode($fields).";更新条件;".  json_decode($where).";执行时间".date('Y-m-d H:i:s',time()));
                $this->CI->user_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_UPDATE_USERINFO_FAIL);
                return false;
            }
        }
        
        //记录用户登录历史记录
        $data3  = array(
            'L_USERIDX'     => $_uuid,
            'L_NAME'        => $params['nickname'],
            'L_CHANNEL'     => $params['channel'],
            'L_SN'          => $params['sn'],
            'L_PASSPORTID'  => $params['passport_id'],
            'L_ENTERTYPE'   => 1,
            'L_IP'          => $this->ip,
            'L_PLATFORM'    => 2,
            'STATUS'        => 0,
        );
        $rst = $this->CI->user_model->insert_data($data3,'bl_loginlog');
        if (!$rst) {
            log_message('error', "do_login:用户登录历史记录插入失败;".$this->CI->input->ip_address().";请求参数params:".  http_build_query($params).";插入字段:".json_encode($data3).";执行时间".date('Y-m-d H:i:s',time()));
            $this->CI->user_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_INSERT_USER_LOGINLOG_FAIL);
            return false;
        }
        $this->CI->user_model->success();
        // 获取用户信息
        $u_info = $this->get_userinfo($_uuid);
        if (!$u_info) {
            return false;
        }
        return $u_info;
    }
    
    /**
     * 
     * 隐形登录（OpenApi的access_token和会员登录信息绑定功能）
     * @param string $passport_id  passport_id
     * @param string $access_token  access_token
     * @param string $sn  设备编号
     * @param string $channel 渠道
     * @param string $key  保存public_key的KEY
     * @return array
     */
    public function login_for_passportid($params_)
    {
        $access_token_key   = $this->CI->passport->get('access_token').$params_['user_id']."_".$params_['channel'];
        $access_token       = $this->CI->cache->memcached->get($access_token_key);
        if (!$access_token) {
            $this->CI->load->library('pay_lib');
            $access_token = $this->CI->pay_lib->get_access_token($params_['sn'],$access_token_key);
        }
        
        $token_info             = json_decode($access_token,true);
        $params['access_token'] = $token_info['accessToken'];
        $params['service_name'] = 'bl.app.member.loginByPassport';
        $params['timestamp']    = time();
        $params['sn']           = $params_['sn'];
        $params['channelId']    = $this->channel;
        $params['sign']         = $this->CI->utility->sha1_sign($params,$token_info['tokenKey']);
        $params['passport_id']  = $params_['session_id'];
        $params['member_token'] = $params_['member_token'];
        if (ENVIRONMENT != 'production') {
            $url    = $this->openapi_url_test;
        } else {
            $url    = $this->openapi_url;
        }
        $url .= "service.htm?openapi_params=".base64_encode(http_build_query($params));
        $header = array(
            "Content-type: application/json", 
        );
        $member_info    = $this->CI->utility->post($url,array(),$header);
        $member_info    = json_decode($member_info,true);
        if ($member_info['resCode'] != '00100000') {
            if ($member_info['errorCode'] == 'BL10012') {// ACCESS_TOKEN失效
                log_message('error', "login_for_passportid_BL10012:ACCESS_TOKEN失效;".$this->CI->input->ip_address().";url:".$url.";return_data:".json_encode($member_info).";执行时间".date('Y-m-d H:i:s',time()));
                $access_token   = $this->get_access_token($params_['sn'],$access_token_key);
                return $data;
            } else {
                $this->CI->error_->set_error(Err_Code::ERR_TOKEN_EXPIRE);
                log_message('error', "login_for_passportid:login_fail登录失败;".$this->CI->input->ip_address().";url:".$url.";return_data:".json_encode($member_info).";执行时间".date('Y-m-d H:i:s',time()));
                return false;
            }
        }
        log_message('info', "login_for_passportid:passportId登录成功;".$this->CI->input->ip_address().";url:".$url.";return_data:".json_encode($member_info).";执行时间".date('Y-m-d H:i:s',time()));
        return $member_info['obj'];
    }
    
    /**
     * 校验用户是否已注册
     */
    public function chk_user_account($user_id)
    {
        $where          = array('U_ACCOUNTID'=>$user_id,'status'=>0);
        $fields         = "U_USERIDX AS uuid";
        $register_info  = $this->CI->user_model->get_one($where, 'bl_userlogin', $fields);
        if ($register_info['uuid']) {
            return $register_info['uuid'];
        }
        return false;
    }
    
    /**
     * 获取用户信息
     * @param type $uuid  用户UUID
     * @return boolean
     */
    public function get_userinfo($uuid)
    {
        $where  = array('IDX'=>$uuid,'status'=>0);
        $fields = "IDX AS uuid,U_NAME AS name ,U_ICON image,U_SEX sex,U_BLCOIN blcoin,U_MOBILEPHONE mobile,U_CHANNEL channel,U_LASTLOGINTIME lastlogin_time,ROWTIME AS create_time";
        $info   = $this->CI->user_model->get_one($where,'bl_user',$fields);
        if (!$info) {
            $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
            return false;
        }
        return $info;
    }
    
    public function get_bluserinfo()
    {
        
    }
    
    
    /**
     * 获取用户收藏游戏数
     * @param type $uuid
     */
    public function get_favorite_num($uuid)
    {
        $where  = array('F_USERIDX'=>$uuid,'STATUS'=>0);
        $table  = "bl_gamefavorites";
        $count  = $this->CI->user_model->total_count($where, $table);
        return $count;
    }
    
    /**
     * 获取用户最新消息数（未读消息）
     * @param type $uuid
     */
    public function get_news_num($uuid)
    {
        $create_time    = $this->CI->utility->get_user_info($uuid,'create_time');
        $sql            = "select COUNT(A.IDX) num FROM bl_mailbox AS A LEFT JOIN bl_mailbox_status AS B ON B.M_USERIDX = ".$uuid." AND A.IDX = B.M_MAILIDX WHERE UNIX_TIMESTAMP(A.ROWTIME) >= ".strtotime($create_time)." AND  A.STATUS = 0  AND B.IDX IS NULL";
        $row            = $this->CI->user_model->fetch($sql);
        return $row['num'];
    }
    
    /**
     * 更新用户信息
     * @param type $uuid   用户UUID
     * @param type $fields 更新字段
     */
    public function update_user_info($uuid,$fields)
    {
        $table  = "bl_user";
        $where  = array('IDX'=>$uuid,'STATUS'=>0);
        $res    = $this->CI->user_model->update_data($fields,$where,$table);
        if (!$res) {
            $this->CI->error_->set_error(Err_Code::ERR_INSERT_BEST_SCORE_FAIL);
            return false;
        }
        return true;
    }
    
    /**
     * 百联币变更历史记录
     */
    public function blcoin_his($data)
    {
        $table      = 'bl_blcoin_his';
        $ist_res    = $this->CI->user_model->insert_data($data,$table);
        if (!$ist_res) {
            $this->CI->error_->set_error(Err_Code::ERR_INSERT_BLCOIN_CHANGE_HIS_FAIL);
            return false;
        }
        return true;
    }
    
    /**
     * 获取我的消息总条数
     */
    public function total_count_of_news($params)
    {
        $create_time    = $this->CI->utility->get_user_info($params['uuid'],'create_time');;
        $where  = array('ROWTIME>='=>$create_time,'STATUS'=>0);
        $table  = "bl_mailbox";
        $count  = $this->CI->user_model->total_count($where, $table);
        return $count;
    }
    
    /**
     * 获取我的消息列表
     * @param type $params
     */
    public function news_list($params)
    {
        // 获取消息总条数
        $create_time    = $this->CI->utility->get_user_info($params['uuid'],'create_time');
        $sql            = "SELECT COUNT(IDX) AS num FROM bl_mailbox  where  STATUS = 0 AND  UNIX_TIMESTAMP(ROWTIME) >= ".strtotime($create_time)." AND IDX NOT IN (SELECT M_MAILIDX FROM bl_mailbox_status WHERE M_STATUS = 1 AND M_USERIDX = ".$params['uuid'].")";
        $total_count    = $this->CI->user_model->fetch($sql);
        if (!$total_count['num']) {
            log_message('info', "favorite_list:暂无消息信息;".$this->CI->input->ip_address().";请求参数params:". http_build_query($params).";执行时间".date('Y-m-d H:i:s',time()));
            return false;
        }
        // $data['pagecount']  = ceil($total_count['num']/$params['pagesize']);
        // 获取消息列表
        $select             = "A.IDX AS id,A.M_NAME AS title,A.M_INFO AS content,A.M_SENDER AS sender,A.ROWTIME AS sender_time,IF(B.IDX,1,0) AS is_read";
        $condition          = "UNIX_TIMESTAMP(A.ROWTIME) >= ".strtotime($create_time)." AND (B.M_STATUS = 0 OR B.M_STATUS IS NULL) AND  A.STATUS = 0";
        $join_condition     = "A.IDX = B.M_MAILIDX AND B.M_USERIDX = ".$params['uuid'];
        $tb_a               = "bl_mailbox AS A";
        $tb_b               = "bl_mailbox_status AS B";
        $data['list']       = $this->CI->user_model->left_join($condition, $join_condition,$select,$tb_a,$tb_b,true);
        
        // 批量更新消息为已读
        foreach ($data['list'] as $k=>$v) {
            if (!$v['is_read']) {
                $ist_data[] = array(
                    'M_MAILIDX' => $v['id'],
                    "M_USERIDX" => $params['uuid'],
                    'M_STATUS'  => 0,
                    'STATUS'    => 0,
                );
            }
        }
        if ($ist_data) {
            $this->CI->user_model->insert_batch($ist_data,"bl_mailbox_status");
        }
        return $data;
    }
    
    /**
     * 删除消息
     * @param type $params
     */
    public function do_delete_news($params)
    {
        $table  = "bl_mailbox_status";
        $fields = array('M_STATUS'=>1);
        $where  = array(
            'M_MAILIDX' => $params['id'],
            "M_USERIDX" => $params['uuid'],
            'M_STATUS'  => 0,
        );
        $res    = $this->CI->user_model->update_data($fields,$where,$table);
        if (!$res) {
            log_message('info', "do_delete_news:消息状态表更新失败;".$this->CI->input->ip_address().";请求参数params:". http_build_query($params).";执行时间".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_MESSAGE_DEL_FAIL);
            return false;
        }
        return true;
    }
    
    
    /**
     * 删除消息
     * @param type $params
     */
    public function do_delete_news1($params)
    {
        $table  = "bl_mailbox_status";
        $data   = array(
            'M_MAILIDX' => $params['id'],
            "M_USERIDX" => $params['uuid'],
            'M_STATUS'  => 1,
            'STATUS'    => 0,
        );
        $res    = $this->CI->user_model->insert_data($data,$table);
        if (!$res) {
            $this->CI->error_->set_error(Err_Code::ERR_MESSAGE_DEL_FAIL);
            return false;
        }
        return true;
    }
    
    /**
     * 获取我的订单列表
     */
    public function order_list($params)
    {
        // 获取数据总页数
        $table          = "bl_order";
        $where          = array('O_USERID'=>$params['uuid'],'STATUS'=>0);
        $total_count    = $this->CI->user_model->total_count($where,$table);
        if (!$total_count) {
            return false;
        }
        $data['pagecount']  = ceil($total_count/$params['pagesize']);
        // 获取列表
        $options['where']   = $where;
        $options['fields']  = "IDX id,O_ORDERNO order_no,O_TYPE type,O_GETBLCOIN blcoin,O_TOTALPRICE order_rmb,O_EXPENDRMB pay_rmb,O_EXOENDPOINT points,O_ORDERSTATUS status,ROWTIME create_time";
        $options['order']   = "IDX DESC";
        $options['limit']   = array('size'=>$params['pagesize'],'page'=>$params['offset']);
        $data['list']       = $this->CI->user_model->list_data($options,$table);
        // 判断待支付订单是否有效
        $expire_time    = $this->CI->passport->get('order_expire');
        $p_merid        = $this->CI->passport->get('p_merid');
        $this->load_library('user_lib');
        $u_info         = $this->CI->utility->get_user_info($params['uuid']);
        foreach ($data['list'] as $k=>&$v) {
            $v['expire_time']   = $expire_time;
            $v['member_id']     = $u_info['user_id'];
            $v['tran_date']     = date('Ymd', strtotime($v['create_time']));
            $v['tran_time']     = date('His',strtotime($v['create_time']));
            $v['callback']      = base_url()."pay/order_callback";
            $v['need_rmb']      = $v['order_rmb'] - $v['pay_rmb'] - $v['points'];
            $v['mer_id']        = $p_merid;
            if ($v['status'] == 2) {
                $v['is_second'] = 1;
            }
        }
        
        return $data;
    }
    
    /**
     * 我的收藏列表
     */
    public function favorite_list($params)
    {
        // 获取收藏总条数
        $where          = "A.IDX = B.F_GAMEIDX AND B.F_USERIDX = ".$params['uuid']." AND A.G_CLOSE = 0 AND B.STATUS = 0 AND A.STATUS = 0";
        $table          = "bl_game AS A,bl_gamefavorites AS B";
        $total_count    = $this->CI->user_model->total_count($where,$table,'A.IDX');
        if (!$total_count) {
            log_message('error', "favorite_list:暂无该收藏列表;".$this->CI->input->ip_address().";请求参数params:". http_build_query($params).";执行时间".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
            return false;
        }
        $data['pagecount']  = ceil($total_count/$params['pagesize']);
        // 获取收藏列表
        $sql        = "SELECT A.IDX id, A.G_GAMEIDX game_id,A.G_NAME name,A.G_ICON icon,G_FILEDIRECTORY file_directory FROM ".$table." WHERE ".$where." ORDER BY B.ROWTIME DESC LIMIT ".$params['offset'].",".$params['pagesize'];
        $list       = $this->CI->user_model->fetch($sql,'result');
        $game_url   = $this->CI->passport->get('game_url');
        foreach ($list as $k=>&$v) {
            if ($v['game_type'] != 2) {// 非网游
                $v['icon']              = $game_url.$v['file_directory'].$v['icon'];
                $v['file_directory']    = $game_url.$v['file_directory']."/play/index.html";
            }
        }
        $data['list']   = $list;
        return $data;
    }
    
    /*
     * 获取兑换记录
     */
    public function get_exchange_his($params)
    {
        $sql_count    = "SELECT count(t1.IDX) as num  FROM bl_exchange_his as t1 LEFT JOIN bl_exchange as t2 on t1.E_EXCHANGEIDX = t2.IDX WHERE t1.E_USERID = ".$params['uuid']." AND t1.`STATUS` = 0 AND t1.E_ESTATUS = 0";
        $sql          = "SELECT t1.IDX as id , t1.E_EXPENDBLCION as blcion , t1.ROWTIME as time , t2.E_PIC as icon , t2.E_NAME as `name` FROM bl_exchange_his as t1 LEFT JOIN bl_exchange as t2 on t1.E_EXCHANGEIDX = t2.IDX WHERE t1.E_USERID = ".$params['uuid']." AND t1.`STATUS` = 0 AND t1.E_ESTATUS = 0 ORDER BY t1.IDX DESC LIMIT ".$params['offset'].",".$params['pagesize'];
        $list['list'] = $this->CI->user_model->fetch($sql,'result');
        $game_url           = $this->CI->passport->get('game_url');
        foreach ($list['list'] as $k => &$v){
            $v['icon'] = $game_url.$this->exchange_img.$v['icon'];
        }
        $list_count   = $this->CI->user_model->fetch($sql_count,'row');
        $list['num']  = ceil($list_count['num']/$params['pagesize']);
        return $list;
    }
    
    /*
     * 删除兑换记录
     */
    public function delete_exchange_his($params)
    {
        $this->CI->user_model->start();
        $fields   = array('STATUS'=>1 , 'E_ESTATUS'=>1);
        $where    = array('IDX'=>$params['id'],'STATUS'=>0);
        $table    = "bl_exchange_his";
        $upt_res    = $this->CI->user_model->update_data($fields,$where,$table);
        if (!$upt_res) {
            log_message('info', "delete_exchange_his:兑换历史记录删除失败;".$this->CI->input->ip_address().";请求参数params:". http_build_query($params).";执行时间".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_MESSAGE_DEL_FAIL);
            $this->CI->user_model->error();
            return false;
        }
        $this->CI->user_model->success();
        return true;
    }
    
    /**
     * 获取用户注册信息
     */
    public function get_register_info($uuid)
    {
        $condition      = "A.IDX = ".$uuid." AND A.STATUS = 0 AND B.STATUS = 0";
        $join_condition = "A.IDX = B.U_USERIDX";
        $select         = "A.IDX uuid,A.U_NAME name,A.U_BLCOIN blcoin,A.U_MOBILEPHONE mobile,A.U_SN sn,A.U_CHANNEL channel,A.U_PASSPORTID passport_id,B.U_ACCOUNTID user_id";
        $info   = $this->CI->user_model->left_join($condition, $join_condition,$select,'bl_user A','bl_userlogin B');
        if (!$info) {
            $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
            return false;
        }
        return $info;
    }
    
    
    
    
}