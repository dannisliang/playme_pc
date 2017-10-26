<?php
class Exchange_lib extends Base_lib {
    public $test_url_of_qg  = 'http://okng.blemall.com/esale/web/slcz_new_test.php?';// 测试地址--全国URL
    public $url_of_qg  = 'http://okng.blemall.com/esale/web/slcz_new.php?';// 正式地址--全国URL
    public $test_url_of_sh  = 'http://okng.blemall.com/esale/web/sjzc_new_test.php?';// 测试地址--上海URL
    public $url_of_sh  = 'http://okng.blemall.com/esale/web/sjzc_new2.php?';// 测试地址--上海URL
    
    public function __construct() {
        parent::__construct();
        $this->CI->load->model('exchange_model');
    }
    
    /**
     * 获取兑换列表
     * @param type $params
     */
    public function get_exchange_list($params)
    {
        $data['mobile_bill']= array();// 手机直充话费
        $data['mobile_flow']= array();// 手机流量
        $table              = 'bl_exchange';
        $options['where']   = array('E_TYPE' => 1,'STATUS'=>0);
        $options['fields']  = "IDX id,E_NAME name,E_ICON icon,E_PIC img,E_EXPENDBLCION blcoin,E_NUM exchange_num,E_NUMTYPE num_type,E_CLOSE close,E_RTYPE type,E_INFO info";
        $list               = $this->CI->exchange_model->list_data($options,$table);
        if (!$list) {
            return false;
        }
        $game_url           = $this->CI->passport->get('game_url');
        foreach ($list as $v) {
            $v['status']    = 1;// 立即兑换
            $v['icon']      = $game_url.$this->exchange_img.$v['icon'];
            $v['img']       = $game_url.$this->exchange_img.$v['img'];
            if ($v['type'] == 1) {// 话费直充
                $data['mobile_bill'][]  = $v;
            } else {
                $data['mobile_flow'][]  = $v;
            }   
        }
        return $data;
    }
    
    /**
     * 执行兑换操作
     * @return int 兑换结果 1return false(兑换失败);2return not_enough(游戏币不足);3return true(兑换成功)
     */
    public function do_exchange($params)
    {
        // 判断是否允许兑换操作
        $condition      = "A.IDX = ".$params['id']." AND A.STATUS = 0";
        $join_condition = "B.E_EXCHANGEIDX = ".$params['id']." AND B.E_USERID = ".$params['uuid']." AND A.IDX = B.E_EXCHANGEIDX";
        $select         = "A.IDX id,A.E_TYPE AS type,A.E_RTYPE rtype,A.E_NAME name,A.E_ICON icon,A.E_PIC img,A.E_EXPENDBLCION blcoin,A.E_NUM exchange_num,A.E_INFO info,A.E_DESCRIPT descript,A.E_NUMTYPE num_type,A.E_CLOSE close,IF(B.IDX,1,0) AS is_exchange,A.E_CARDTYPE dtype,A.E_RECHARGENUM dczsl";
        $tb_a           = "bl_exchange AS A";
        $tb_b           = "bl_exchange_his AS B";
        $info           = $this->CI->exchange_model->left_join($condition,$join_condition,$select,$tb_a,$tb_b);
        if (!$info) {
            log_message("error", "do_exchange:暂未查询到改兑换信息;".$this->CI->input->ip_address().";请求参数params：".  http_build_query($params).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_NOT_EXISTS_FAIL);
            return false;
        }
        if ($info['close'] == 1) {
            log_message("info", "do_exchange:该兑换券已关闭不允许兑换;".$this->CI->input->ip_address().";请求参数params：".  http_build_query($params).";查询结果：".  json_encode($info).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_NOT_ALLOW_EXCHANGE_FAIL);
            return false;
        }
        if ($info['is_exchange'] && $info['num_type'] == 1) {// 已兑换、不允许兑换（单次兑换）
            log_message("info", "do_exchange:该用户已兑换，不允许兑换'单次兑换商品';请求参数params：".  http_build_query($params).";查询结果：".  json_encode($info).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_NOT_ALLOW_REPEAT_EXCHANGE_FAIL);
            return false;
        }
        
        // 查询商品兑换状态：是否有货
        $e_info = $this->exchange_query(array('dtype'=>$info['dtype']));
        if ($e_info != 'on') {
            log_message("info", "do_exchange:兑换商品状态为：关闭;".$this->CI->input->ip_address().";请求参数：".  http_build_query($params).";查询结果：".  json_encode($e_info).";执行时间：".date('Y-m-d H:i:s',time()));
            // 关闭兑换商品
            $fields_3   = array('E_CLOSE'=>1);
            $where_3    = array('E_CARDTYPE'=>$info['dtype'],'STATUS'=>0);
            $res        = $this->CI->exchange_model->update_data($fields_3,$where_3);
            if (!$res) {
                log_message("error", "do_exchange:关闭兑换商品失败;".$this->CI->input->ip_address().";请求参数：".  http_build_query($params).";查询结果：".  json_encode($info).";执行时间：".date('Y-m-d H:i:s',time()));
                $this->CI->exchange_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_CLOSE_FAIL);
                return false;
            }
            return false;
        }
        
        // 校验用户百联币，并开启行锁
        $this->CI->exchange_model->start();
        $sql    = "SELECT IDX uuid,U_NAME name,U_BLCOIN blcoin FROM bl_user WHERE IDX = ".$params['uuid']. " AND STATUS = 0 FOR UPDATE";
        $u_info = $this->CI->exchange_model->fetch($sql,'row');
        if (!$u_info) {
            log_message('error', "do_exchange:用户信息查询失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";执行sql:".$sql.";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->pay_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
            return false;
        }
        if ($u_info['blcoin'] < $info['blcoin']) {
            log_message('error', "do_exchange:用户百联币不足;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";return_data:".  json_encode($u_info).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_BLCOIN_NOT_ENOUGHT_FAIL);
            return 'not_enough';
        }
        
        // 扣除用户游戏币
        $this->CI->load->library('user_lib');
        $fields = array('U_BLCOIN'=>$u_info['blcoin']-$info['blcoin']);
        $upt_u  = $this->CI->user_lib->update_user_info($params['uuid'],$fields);
        if (!$upt_u) {
            log_message('error', "do_exchange:用户百联币扣除失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->exchange_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_BLCOIN_DEDUCT_FAIL);
            return false;
        }
        
        // 记录游戏币变更历史记录
        $bl_data    = array(
            'G_USERIDX'     => $params['uuid'],
            'G_NICKNAME'    => $u_info['name'],
            'G_TYPE'        => 1,
            'G_SOURCE'      => 0,
            'G_BLCOIN'      => $info['blcoin'],
            'G_TOTALBLCOIN' => $u_info['blcoin'] - $info['blcoin'],
            'G_INFO'        => '兑换消耗'.$info['blcoin']."游戏币",
            'STATUS'        => 0,
        );
        $ist_res    = $this->CI->utility->blcoin_change_his($bl_data);
        if (!$ist_res) {
            log_message('error', "do_exchange:插入百联币变更历史记录失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入数据：".  json_encode($bl_data).";return_data:".$ist_res.";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->exchange_model->error();
            return false;
        }
        
        // 记录兑换历史记录
        $data   = array(
            'E_USERID'          => $params['uuid'],
            'E_NICKNAME'        => $u_info['name'],
            'E_EXCHANGEIDX'     => $params['id'],
            'E_TYPE'            => $info['type'],
            'E_MOBILE'          => $params['mobile'],
            'E_EXPENDBLCION'    => $info['blcoin'],
            'E_ESTATUS'         => 0,
            'STATUS'            => 0,
        );
        $params['order_id']    = $this->CI->exchange_model->insert_data($data,'bl_exchange_his');
        if (!$params['order_id']) {
            log_message('error', "do_exchange:插入兑换历史记录失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入数据：".  json_encode($data).";return_data:".$params['order_id'].";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->exchange_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_INSERT_EXCHANGE_HIS_FAIL);
            return false;
        }
        
        // 插入兑换消息表
        $para['E_USERID']       = $params['uuid'];
        $para['E_NICKNAME']     = $u_info['name'];
        $para['E_EXCHANGEIDX']  = $params['id'];
        $para['E_EXCHANGENAME'] = $info['name'];
        $res    = $this->insert_exchange_mess($para);
        if (!$res) {
            log_message('error', "do_exchange:插入兑换快报消息表失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入数据：".  json_encode($para).";return_data:".$res.";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->exchange_model->error();
            return false;
        }
        
        // 修改兑换券兑换次数
        $fields_2 = array('E_NUM'=>$info['exchange_num']+1);
        $where_2  = array('IDX'=>$params['id'],'STATUS'=>0);
        $upt_e    = $this->CI->exchange_model->update_data($fields_2,$where_2,'bl_exchange');
        if (!$upt_e) {
            log_message('error', "do_exchange:更新兑换次数失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";更新字段:".  json_encode($fields_2).";更新条件：".  json_encode($where_2).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->exchange_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_UPDATE_EXCHANGE_NUM_FAIL);
            return false;
        }
        
        // 百联接口兑换操作
        $params['dtype']        = $info['dtype'];
        $params['redirec_type'] = $info['dczsl'];
        if ($info['type'] == 1) {// 执行直充兑换操作（调用百联兑换接口：流量、话费）TOTO
            if ($info['rtype'] == 1) {// 话费直充
                $res    = $this->do_mobile_fare_exchange($params);
                if (!$res) {
                    $this->CI->exchange_model->error();
                    return false;
                }
            } else {// 流量直充
                $res    = $this->do_flow_fare_exchange($params);
                if (!$res) {
                    $this->CI->exchange_model->error();
                    return false;
                }
            }
        } else if($info['type'] == 2) {// 优惠券
            $res    = $this->do_coupon_exchange_by_bl($params);
            if (!$res) {
                $this->CI->exchange_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_DO_THIRDPARTY_EXCHANGE_FAIL);
                return false;
            }
        }
        $this->CI->exchange_model->success();
        return true;
    }
    
    /**
     * 直充话费兑换操作--调用百联接口
     */
    public function do_mobile_fare_exchange($params)
    {
        $para['dshh']       = $this->CI->passport->get('merid');// 商户号
        $para['outorderid'] = $params['order_id'];
        $para['mobile']     = $params['mobile'];
        $key                = $this->CI->passport->get('secret');
        $para['dczsl']      = $params['redirec_type'];// 直充数值类型
        $para['dtype']      = $params['dtype'];
        $para['zflx']       = '';// ok为ok账户,不填则为现金账户 当dtype=03时zflx=lt 为联通按元充账户
        
        // 判断手机号
        $where_url          = "http://okng.blemall.com/esale/web/mobileinfo.php?mobile=";
        $where_res          = $this->CI->utility->get($where_url.$para['mobile']);
        $where_res          = iconv('GBK','UTF-8',$where_res);
        $where_arr          = explode("|", $where_res);
        if (!$where_arr[1]) {// 充值的号码格式不正确
            log_message('error', "do_mobile_fare_exchange:充值的号码格式不正确;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_MOBILE_FOMAT_FAIL);
            return false;
        }
        if ($params['dtype'] == '09') {// 全国--不允许充值上海手机号
            if ($where_arr[1] == '上海上海') {// 不允许充值上海手机号
                $this->CI->error_->set_error(Err_Code::ERR_NOT_ALLOW_EXCHANGE_SH_MOBILE);
                return false;
            } else {
                $para['zflx']   = 'xj';
                if (ENVIRONMENT != 'production') {
                    $url    = $this->test_url_of_qg;
                } else {
                    $url    = $this->url_of_qg;
                }
                $para['dtype']      = 16;
                $para['sign']       = md5($para['dshh'].$para['outorderid'].$para['mobile'].$para['dczsl'].$para['dtype'].$para['zflx'].$key);
            }
        }else {
            $para['sdsj']       = date('YmdHis',time());// 商户实际收单时间，格式YYYYMMDDHHMMSS 例子20121218104423
            $para['msgtype']    = 'json';
            $para['sign']       = md5($para['dshh'].$para['outorderid'].$para['mobile'].$para['dczsl'].$para['dtype'].$para['zflx'].$para['msgtype'].$key);
            if (ENVIRONMENT != 'production') {
                $url    = $this->test_url_of_sh;
            } else {
                $url    = $this->url_of_sh;
            }
        }
        $url    = $url.http_build_query($para);
        $result = $this->CI->utility->post($url);
        $result = iconv('GBK','UTF-8',$result);
        $result = json_decode($result,true);
        if ($result['code'] == '01') {// 请求成功
            log_message('info', "do_exchange:用户直充话费兑换成功;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";url：".  $url.";return_data:".  json_encode($result).";执行时间：".date('Y-m-d H:i:s',time()));
            return true;
        }elseif ($result['code'] == '-1') {
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_SERVICE_DOWN_FAIL);
        }elseif ($result['code'] == '-2') {
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_PARA_FAIL);
        }elseif ($result['code'] == '-3') {
            $this->CI->error_->set_error(Err_Code::ERR_NOT_MOVENUMBER_FAIL);
        }elseif ($result['code'] == '-4') {
            $this->CI->error_->set_error(Err_Code::ERR_ONLY_MOVENUMBER_FAIL);
        }elseif ($result['code'] == '-5') {
            $this->CI->error_->set_error(Err_Code::ERR_MERCHANT_TOP_FAIL);
        }elseif ($result['code'] == '-6') {
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_SIGN_FAIL);
        }elseif ($result['code'] == '-7') {
            $this->CI->error_->set_error(Err_Code::ERR_NOT_ALLOW_IP_FAIL);
        }elseif ($result['code'] == '-8') {
            $this->CI->error_->set_error(Err_Code::ERR_MERCHANT_NOT_EXISTS_FAIL);
        }elseif ($result['code'] == '-9') {
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_REQUEST_ABNORMAL_FAIL);
        }elseif ($result['code'] == '-10') {
            $this->CI->error_->set_error(Err_Code::ERR_DO_EXCHANGE_NOT_ALLOW_FAIL);
        }elseif ($result['code'] == '-11') {
            $this->CI->error_->set_error(Err_Code::ERR_NOT_COUNTRYWIDE_NUMBER_FAIL);
        }elseif ($result['code'] == '-12') {
            $this->CI->error_->set_error(Err_Code::ERR_REMAINDER_NOT_ENOUGHT_FAIL);
        }elseif ($result['code'] == '-13') {
            $this->CI->error_->set_error(Err_Code::ERR_ABOVE_QUOTA_FAIL);
        } else {
             $this->CI->error_->set_error(Err_Code::ERR_MOBILE_EXHCANGE_FAIL);
        }
        log_message('error', "do_exchange:用户只充话费兑换失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";url：".  $url.";return_data:".  json_encode($result).";执行时间：".date('Y-m-d H:i:s',time()));
        return false;
    }
    
    /**
     * 直充流量兑换操作--调用百联接口
     * @param type $params
     */
    public function do_flow_fare_exchange($params)
    {
        $para['dshh']       = $this->CI->passport->get('merid');// 商户号
        $para['outorderid'] = $params['order_id'];
        $para['mobile']     = $params['mobile'];
        $key                = $this->CI->passport->get('secret');
        $para['dczsl']      = $params['redirec_type'];// 直充数值类型
        $para['dtype']      = $params['dtype'];
        $para['zflx']       = 'xj';// ok为ok账户,不填则为现金账户 当dtype=03时zflx=lt 为联通按元充账户
        $para['sign']   = md5($para['dshh'].$para['outorderid'].$para['mobile'].$para['dczsl'].$para['dtype'].$para['zflx'].$key);
        if (ENVIRONMENT != 'production') {
            $url    =   'http://okng.blemall.com/esale/web/jfcz_new_test.php?';
        } else {
            $url    = 'http://okng.blemall.com/esale/web/jfcz_new.php?';
        }
        $url    = $url.http_build_query($para);
        $result = $this->CI->utility->post($url);
        $result = iconv('GBK','UTF-8',$result);
        $result = json_decode($result,true);
        if ($result['code'] == '01') {// 请求成功
            log_message('info', "do_exchange:用户流量兑换成功;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";url：".  $url.";return_data:".  json_encode($result).";执行时间：".date('Y-m-d H:i:s',time()));
            return true;
        }elseif ($result['code'] == '-5') {
            $this->CI->error_->set_error(Err_Code::ERR_MERCHANT_TOP_FAIL);
        }elseif ($result['code'] == '-6') {
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_SIGN_FAIL);
        }elseif ($result['code'] == '-7') {
            $this->CI->error_->set_error(Err_Code::ERR_NOT_ALLOW_IP_FAIL);
        }elseif ($result['code'] == '-8') {
            $this->CI->error_->set_error(Err_Code::ERR_MERCHANT_NOT_EXISTS_FAIL);
        } else {
             $this->CI->error_->set_error(Err_Code::ERR_FLOW_EXHCANGE_FAIL);
        }
        log_message('error', "do_exchange:用户流量兑换失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";url：".  $url.";return_data:".  json_encode($result).";执行时间：".date('Y-m-d H:i:s',time()));
        return false;
    }
    
    /**
     * 插入兑换消息信息
     */
    public function insert_exchange_mess($params)
    {
        $table  = "bl_exchange_news";
        // 插入快报消息
        $ist_res    = $this->CI->exchange_model->insert_data($params,$table);
        if (!$ist_res) {
            $this->CI->error_->set_error(Err_Code::ERR_INSERT_EXCHANGE_MESS_FAIL);
            return false;
        }
        return true;
    }
    
    /**
     * 第三方兑换状态查询
     */
    public function exchange_query($params)
    {
        $para['dshh']   = $this->CI->passport->get('merid');// 商户号
        $para['dtype']  = $params['dtype'];
        $url            = "http://okng.blemall.com/esale/web/getStatus.php";
        $result         = $this->CI->utility->get($url,$para);
        log_message("info", "exchange_query:第三方兑换状态查询;请求参数".  http_build_query($params).";url:".$url.";return:".$result.";执行时间：".date('Y-m-d H:i:s',time()));
        $result         = json_decode($result,true);
        if ($result['code'] != '200') {// 状态查询失败
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_BUILDING_FAIL);
            return 'off';
        }
        if ($result['status'] == 'off') {
            $this->CI->error_->set_error(Err_Code::ERR_EXCHANGE_BUILDING_FAIL);
            return 'off';
        }
        return 'on';
    }
    
    /**
     * 脚本自动检测
     */
    public function exchange_query_handle()
    {
        $table              = "bl_exchange";
        $options['fields']  = "IDX id,E_CARDTYPE dtype,E_CLOSE close";
        $options['where']   = array('E_CLOSE'=>1,'STATUS'=>0);
        $list               = $this->CI->exchange_model->list_data($options,$table);
        if (!$list) {
            return true;
        }
        $info = array_column($list, 'dtype','id');
        // 恢复置灰操作
        $data  = array();
        $info_ = array_unique($info);
        foreach ($info_ as $v) {
            // 查询改dtype兑换类型，是否有货
            $result     = $this->exchange_query(array('dtype'=>$v));
            $result     = 'on';
            if ($result == 'on') {
                foreach ($info as $key=>$val) {
                    if ($val == $v) {
                        $data[] = array('IDX'=>$key,'E_CLOSE'=>0);
                    }
                }
            }
        }
        if (!$data) {
            return true;
        }
        $upt_res    = $this->CI->exchange_model->update_batch($data, "IDX",$table);
        if (!$upt_res) {
            log_message('error', '兑换置灰批量开启失败');
            return false;
        }
        return true;
    }
    
    
    
    
    
}