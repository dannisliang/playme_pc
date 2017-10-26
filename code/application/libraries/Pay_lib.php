<?php
class Pay_lib extends Base_lib {
    public function __construct() {
        parent::__construct();
        $this->CI->load->model('pay_model');
        $this->CI->load->library('user_lib');
    }
    
    /**
     * 百联币充值接口(生成订单)
     * @param type $params
     */
    public function do_generate_order($params)
    {
        // 校验用户积分是否足够
        $type           = 1;
        $is_second      = 1;
        $order_status   = 2;
        $point_blcoin_rate  = $this->CI->passport->get('blcoin_point_rate');
        $need_points        = round($params['blcoin']/$point_blcoin_rate);
        if ($params['points']) {
            $point_info = $this->query_bl_point($params['uuid']);
            if ($params['points'] > $point_info['points']) {// 账户积分不足
                log_message('error', "do_generate_order:账户积分不足;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";用户积分信息:".  json_encode($point_info).";执行时间：".date('Y-m-d H:i:s',time()));
                $this->CI->error_->set_error(Err_Code::ERR_USER_POINT_NOT_ENOUGH);
                return false;
            }
            if ($params['points'] >= $need_points) {
                $type           = 2;
                $is_second      = 0;
                $order_status   = 1;
                $params['points']   = $need_points;
            } else {
                $type   = 3;
                $is_second  = 1;
            }
        }
        
        // 生成订单号
        $rate       = $this->CI->passport->get('blcoin_rate');
        $total_rmb  = round($params['blcoin']/$rate);// 应 需要总人民币
        $need_rmb   = (($params['blcoin']-$params['points'])*$rate);// 实 需要总人民币
        $order_no   = $this->generate_order_id($params['uuid']);// 生成订单编号
        $time       = time();
        // 执行充值操作
        $this->CI->pay_model->start();
        // 插入订单表
        $ist_data   = array(
            'O_ORDERNO'     => $order_no,
            'O_TYPE'        => 1,
            'O_USERID'      => $params['uuid'],
            'O_TOTALPRICE'  => $total_rmb,
            'O_EXPENDRMB'   => 0,
            'O_EXOENDPOINT' => $params['points'],
            'O_GETBLCOIN'   => $params['blcoin'],
            'O_FEETYPE'     => $type,
            'O_ORDERSTATUS' => $order_status,
            'O_EXT'         => '',
            'O_CALLBACK'    => 0,
            'O_INFO'        => '',
            'O_PLATFORM'    => 2,// 订单平台1APP 2PC
            'STATUS'        => 0,
            'ROWTIME'       => date('Y-m-d H:i:s',$time),
            'ROWTIMEUPDATE' => date('Y-m-d H:i:s',$time),
        );
        $order_id   = $this->CI->pay_model->insert_data($ist_data,'bl_order',0);
        if (!$order_id) {
            log_message('error', "do_generate_order:充值订单插入失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入数据:".  json_encode($ist_data).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->pay_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_PAY_ORDER_INSERT_FAIL);
            return false;
        }
        // $u_info = $this->CI->user_lib->get_register_info($params['uuid']);
        if ($params['points']) {
            // 判断订单是否 支付全额-修改用户百联币值
            if ($order_status == 1) {
                // 查询用户信息-并执行行锁
                $sql    = "SELECT IDX uuid,U_NAME name,U_BLCOIN blcoin FROM bl_user WHERE IDX = ".$params['uuid']. " AND STATUS = 0 FOR UPDATE";
                $u_info = $this->CI->pay_model->fetch($sql,'row');
                if (!$u_info) {
                    log_message('error', "do_generate_order:用户信息查询失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";执行时间：".date('Y-m-d H:i:s',time()));
                    $this->CI->pay_model->error();
                    $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
                    return false;
                }
                
                // 增加用户百联币数
                $sql        = "UPDATE bl_user SET U_BLCOIN = ".($params['blcoin']+$u_info['blcoin'])." WHERE IDX=".$params['uuid']." AND STATUS = 0";
                $upt_res    = $this->CI->pay_model->fetch($sql,'update');
                if (!$upt_res) {
                    log_message('error', "do_generate_order:用户百联币增加失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).",sql:".$sql.";执行时间：".date('Y-m-d H:i:s',time()));
                    $this->CI->pay_model->error();
                    $this->CI->error_->set_error(Err_Code::ERR_PAY_BLCOIN_FAIL);
                    return false;
                }
                
                // 百联币变更历史记录
                $bl_data    = array(
                    'G_USERIDX'     => $params['uuid'],
                    'G_NICKNAME'    => $u_info['name'],
                    'G_TYPE'        => 0,
                    'G_SOURCE'      => 1,
                    'G_BLCOIN'      => $params['blcoin'],
                    'G_TOTALBLCOIN' => $u_info['blcoin'] + $params['blcoin'],
                    'G_INFO'        => '充值获得'.$params['blcoin']."游戏币",
                    'STATUS'        => 0,
                );
                $this->CI->load->library('game_lib');
                $ist_res    = $this->CI->game_lib->blcoin_change_his($bl_data);
                if (!$ist_res) {
                    log_message('error', "do_generate_order:百联币变更历史记录插入失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).",插入数据:".  json_encode($bl_data).";执行时间：".date('Y-m-d H:i:s',time()));
                    $this->CI->pay_model->error();
                    return false;
                }
                
                // 插入订单充值历史记录
                $ist_data   = array(
                    'R_ORDERIDX'    => $order_id,
                    'R_ORDERNO'     => $order_no,
                    'R_TYPE'        => 1,
                    'R_USERID'      => $params['uuid'],
                    'R_TOTALPRICE'  => $total_rmb,
                    'R_EXPENDRMB'   => 0,
                    'R_EXOENDPOINT' => (int)$params['points'],
                    'R_GETBLCOIN'   => (int)$params['blcoin'],
                    'R_FEETYPE'     => 2,
                    'R_ORDERSTATUS' => 1,
                    'R_PLATFORM'    => 2,// 充值平台1APP2PC
                    'STATUS'        => 0,
                );
                $ist_res    = $this->CI->pay_model->insert_data($ist_data,'bl_recharge_his');
                if (!$ist_res) {
                    log_message('error', "do_generate_order:插入订单充值历史记录失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).",插入数据:".  json_encode($ist_data).";执行时间：".date('Y-m-d H:i:s',time()));
                    $this->CI->pay_model->error();
                    $this->CI->error_->set_error(Err_Code::ERR_INSERT_RECHARGE_HIS_FAIL);
                    return false;
                }
            }
            // 2.调用第三方百联接口--扣除积分
            $n_params['order_id']       = $order_no;
            $n_params['tran_date']      = date('Ymd',$time);
            $n_params['tran_time']      = date('His',$time);
            $n_params['order_time']     = date('YmdHis',$time);
            $n_params['uuid']           = $params['uuid'];
            $n_params['points']         = $params['points'];// 已支付积分
            $n_params['total_price']    = $need_points;// 订单总价格（人民币）
            $n_params['pay_price']      = $params['points'];// 已支付人民币
            $res    = $this->do_pay_by_points($n_params);
            if (!$res) {
                log_message('error', 'do_generate_order:游戏币充值-积分扣除失败'.$this->CI->input->ip_address().',请求参数params:'.  json_encode($params)."执行时间：".date('Y-m-d H:i:s',time()));
                $this->CI->pay_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_POINTS_REDUCE_FAIL);
                return false;
            }
            // 记录积分变更历史记录
            $point_data = array(
                'G_USERIDX'     => $params['uuid'],
                'G_NICKNAME'    => $u_info['name'],
                'G_TYPE'        => 1,// 类型0:增加1:减少
                'G_SOURCE'      => 0,// 变更来源0:充值抵扣1补签消耗2:签到获得
                'G_POINT'       => $params['points'],
                'G_INFO'        => '充值百联币'.$params['blcoin'].',抵扣'.$params['points']."积分",
                'STATUS'        => 0,
            );
            $point_his_id   = $this->CI->user_model->insert_data($point_data,'bl_point_his'); 
            if (!$point_his_id) {
                log_message('error', 'do_generate_order:积分变更历史记录插入失败'.$this->CI->input->ip_address().',请求参数params:'.  json_encode($params).";插入数据:".  json_encode($point_data).";执行时间：".date('Y-m-d H:i:s',time()));
                $this->CI->user_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_INSERT_POINT_CHANGE_HIS_FAIL);
                $this->CI->output_json_return();
            }
        }
        $this->CI->pay_model->success();
        // 返回订单信息
        $data   = array(
            'id'            => $order_id,
            'order_no'      => $order_no,
            'member_id'     => $u_info['user_id'],
            'mer_id'        => $this->CI->passport->get('p_merid'),
            'time'          => $time,
            'order_rmb'     => $total_rmb,
            'need_rmb'      => $need_rmb,
            'points'        => $params['points'],
            'expire_time'   => $this->CI->passport->get('order_expire'),
            'callback'      => base_url()."pay/callback_back",
            'is_second'     => $is_second,
        );
        return $data;
    }
    
    /**
     * 积分支付接口
     * @param array $params 支付参数
     * @param int $int 控制递归回调次数
     * @return boolean
     */
    public function do_pay_by_points($params,$int = 0)
    {
        $u_info = $this->CI->user_lib->get_register_info($params['uuid']);
        $a_key  = $this->CI->passport->get('access_token').$u_info['user_id']."_".$u_info['channel'];
        $access_token   = $this->CI->cache->memcached->get($a_key);
        if (!$access_token) {
            $access_token = $this->get_access_token($u_info['sn'],$a_key);
        }
        $token_info    = json_decode($access_token,true);
        
        $para['access_token']   = $token_info['accessToken'];
        $para['service_name']   = 'bl.app.pay.pointPay';
        $para['timestamp']      = time();
        $para['sn']             = $u_info['sn'];
        $para['channelId']      = $this->channel;
        $para['sign']           = $this->CI->utility->sha1_sign($para,$token_info['tokenKey']);
        if (ENVIRONMENT != 'production') {
            $url    = $this->openapi_url_test;
        } else {
            $url    = $this->openapi_url;
        }
        $url                    = $url."service.htm?openapi_params=".base64_encode(http_build_query($para));
        $data['memberId']       = $u_info['user_id'];
        $data['BankInstNo']     = '700000000000027';
        $data['BusiType']       = '0001';
        $data['CommodityMsg']   = '百联币充值';
        $data['DiscountAmt']    = '0';
        $data['MarAfterUrl']    = base_url()."pay/callback_back";
        $data['MarFrontUrl']    = base_url()."pay/callback_front";
        $data['MerId']          = (string)$this->CI->passport->get('p_merid');
        $data['MerOrderNo']     = (string)$params['order_id'];
        $data['MerResv']        = '游戏充值';
        $data['OrderAmt']       = (string)$params['total_price'];
        $data['PayAmt']         = (string)$params['pay_price'];
        $data['PayTimeOut']     = (string)($this->CI->passport->get('order_expire')/60);
        $data['TranDate']       = (string)$params['tran_date'];
        $data['TranTime']       = (string)$params['tran_time'];
        $data['TranType']       = '0011';
        $data['Version']        = '20140728';
        $data['blchannelId']    = '1';
        $data['validatecode']   = '2';
        $str                    = rtrim($this->output_sign($data),"&");
        $p_secret               = $this->CI->passport->get('p_secret');
        $key                    = strtoupper(md5($p_secret));
        $data['Signature']      = strtoupper(openssl_digest($str.$key, 'sha256'));
        $header = array(
            "Content-type: application/json", 
        );
        $content                = $this->CI->utility->post($url,json_encode($data),$header);
        $content_arr            = json_decode($content,true);
        if ($content_arr['respCode'] != '0000') {
            if ($content_arr['errorCode'] == 'BL10012') {// ACCESS_TOKEN失效
                log_message('error', 'update_bl_point:update_blpoint_failBL10012'.$this->CI->input->ip_address().',递归回调次数i:'.$int.';百联积分支付更新失败;uuid:'.$params['uuid'].';URL:'.$url.";request_data:".  json_encode($data).";return_data:".$content."执行时间：".date('Y-m-d H:i:s',time()));
                if ($int < 2) {
                    $access_token   = $this->get_access_token($u_info['sn'],$a_key);
                    $data           = $this->do_pay_by_points($params,$int+1);
                    return $data;
                }
                $this->CI->error_->set_error(Err_Code::ERR_POINTS_REDUCE_FAIL);
                return false;
            } else {
                $this->CI->error_->set_error(Err_Code::ERR_POINTS_REDUCE_FAIL);
                log_message('error', 'update_bl_point:update_blpoint_fail'.$this->CI->input->ip_address().',百联积分支付更新失败;uuid:'.$params['uuid'].';URL:'.$url.";request_data:".  json_encode($data).";return_data:".$content."执行时间：".date('Y-m-d H:i:s',time()));
                return false;
            }
        } else {
            if ($data['PayAmt'] != $content_arr['points']) {
                $this->CI->error_->set_error(Err_Code::ERR_POINTS_REDUCE_FAIL);
                log_message('error', 'update_bl_point:update_blpoint_fail'.$this->CI->input->ip_address().','.$content_arr['respCode'].'百联积分支付时扣除积分不一致,应扣:'.$data['PayAmt'].',实扣:'.$content_arr['point'].';uuid:'.$params['uuid'].';URL:'.$url.";request_data:".  json_encode($data).";return_data:".$content."执行时间：".date('Y-m-d H:i:s',time()));
                return false;
            }
        }
        log_message('info', 'update_bl_point:游戏币充值-积分扣除成功'.$this->CI->input->ip_address().',请求参数params:'.  json_encode($params).';URL:'.$url.";request_data:".  json_encode($data).";return_data:".$content."执行时间：".date('Y-m-d H:i:s',time()));
        return true;
    }
    
    
    /**
     * 订单支付接口（支付人民币）
     */
    public function do_pay_again($params)
    {
        // 校验订单是否允许支付
        $table  = "bl_order";
        $where  = array('IDX'=>$params['id'],'status'=>0);
        $o_info = $this->CI->pay_model->get_one($where,$table);
        if ($o_info['O_ORDERSTATUS'] != 2) {
            $this->CI->error_->set_error(Err_Code::ERR_ORDER_NOT_ALLOW_BUY);
            return false;
        }
        
        // 执行支付操作(调用百联接口--展示订单页面)
        $params['uuid']         = $o_info['O_USERID'];
        $params['order_rmb']    = $o_info['O_TOTALPRICE'];
        $params['time']         = strtotime($o_info['ROWTIME']);
        $params['order_no']     = $o_info['O_ORDERNO'];
        $this->do_pay_by_rmb($params);
    }
    
    /**
     * 调用支付界面
     */
    public function do_pay_by_rmb($params)
    {
        $u_info = $this->CI->user_lib->get_register_info($params['uuid']);
        $data['allNeedMoney']   = (string)($params['order_rmb']/100);// 订单金额:单位元
        $data['discountMoney']  = (string)($params['order_rmb']/100);//
        $data['endTime']        = (string)($this->CI->passport->get('order_expire')/60);
        $data['marAfterUrl']    = base_url()."pay/callback_back";
        $data['marFrontUrl']    = base_url()."pay/callback_front";
        $data['memberId']       = (string)$u_info['user_id'];
        $data['merId']          = $this->CI->passport->get('p_merid');
        $data['orderStatus']    = '';
        $data['orderStatusDesc']= '';
        $data['orderTime']      = date('Y-m-d H:i:s',$params['time']);
        $data['orderType']      = '百联币充值订单';
        $data['paySerialNo']    = $params['order_no'];
        $data['payWayList']     = '';
        $data['subId']          = '';
        $data['productId']      = "1";
        $str                    = rtrim($this->output_sign($data),"&");
        $p_secret               = $this->CI->passport->get('p_secret');
        $data['sign']           = md5($str.$p_secret);
        if (ENVIRONMENT != 'production') {
            $url    = $this->pay_url_test;
        } else {
            $url    = $this->pay_url;
        }
        $url    = $url."?payResDto=".  json_encode($data);
        redirect($url);exit;
    }
    
    /**
     * 查看百联账户积分
     * @param int $uuid 用户id
     * @param int $int  用于控制递归调用次数
     */
    public function query_bl_point($uuid,$int = 0)
    {
        $u_info         = $this->CI->user_lib->get_register_info($uuid);
        $a_key          = $this->CI->passport->get('access_token').$u_info['user_id']."_".$u_info['channel'];
        $access_token   = $this->CI->cache->memcached->get($a_key);
        if (!$access_token) {
            $access_token = $this->get_access_token($u_info['sn'],$a_key);
        }
        $token_info             = json_decode($access_token,true);
        $para['access_token']   = $token_info['accessToken'];
        $para['service_name']   = 'bl.member.core.querymemberpoint';
        $para['timestamp']      = time();
        $para['sn']             = $u_info['user_id'];// PC端sn=memberId
        $para['channelId']      = $this->channel;
        $para['sign']           = $this->CI->utility->sha1_sign($para,$token_info['tokenKey']);
        $data['sysid']          = '2103';
        if (ENVIRONMENT != 'production') {
            $url    = $this->openapi_url_test;
        } else {
            $url    = $this->openapi_url;
        }
        $url    = $url."service.htm?openapi_params=".base64_encode(http_build_query($para));
        $header = array(
            "Content-type: application/json", 
        );
        $content                = $this->CI->utility->post($url,json_encode($data),$header);
        $content_arr            = json_decode($content,true);
        if ($content_arr['resCode'] != '00100000') {
            if ($content_arr['errorCode'] == 'BL10012') {// ACCESS_TOKEN失效
                log_message('error', 'query_bl_point_BL10012:百联积分查询失败之ACCESS_TOKEN失效;'.$this->CI->input->ip_address().',递归执行次数i:'.$int.';URL:'.$url.';require_data:'.  json_encode($data).';return_data:'.$content);
                if ($int < 2) {
                    $access_token   = $this->get_access_token($u_info['sn'],$a_key);
                    $data           = $this->query_bl_point($uuid,$int+1);
                    return $data;
                }
                $this->CI->error_->set_error(Err_Code::ERR_GET_BLPOINT_FAIL);
                return false;
            } else {
                $this->CI->error_->set_error(Err_Code::ERR_GET_BLPOINT_FAIL);
                log_message('error', 'query_bl_point:百联积分查询失败;'.$this->CI->input->ip_address().',递归执行次数i:'.$int.';URL:'.$url.';require_data:'.  json_encode($data).';return_data:'.$content);
                return false;
            }
        }
        log_message('info', 'query_bl_point:百联积分查询成功;'.$this->CI->input->ip_address().',递归执行次数i:'.$int.';URL:'.$url.';require_data:'.  json_encode($data).';return_data:'.$content);
        return $content_arr['obj'];
    }
    
    
    /**
     * 积分支付接口，sign加密
     * @param type $params
     */
    public function output_sign($params)
    {
        foreach ($params as $key => $val) {
            if ($val === "") {
                continue;
            }
            $para[$key] = $params[$key];
        }
        ksort($para);
        $arg = '';
        foreach ($para as $k=>$v) {
            $arg .= $k.'='.$v.'&';
        }
        return $arg;
    }
    
    
    /**
     * 生成订单编号
     */
    public function generate_order_id($uuid)
    {
        return "BLO".uniqid().  rand(10, 99);
    }
    
    /**
     * 生成充值sign
     * @param type $params
     * @return type
     */
    public function get_Signature($params)
    {
        foreach ($params as $key => $val) {
            $para[$key] = $params[$key];
        }
        ksort($para);
        $arg = '';
        foreach ($para as $k=>$v) {
            $arg .= $k.'='.$v.'&';
        }
        $sign_key = '';// MD5的key值由百联支付中台分配
        $arg .= $sign_key;
        return md5($arg);
    }
    
    /**
     * 获取百联币充值信息
     */
    public function get_recharge_info($params)
    {
        $rate               = $this->CI->passport->get('blcoin_rate');
        $point_blcoin_rate  = $this->CI->passport->get('blcoin_point_rate');
        
        // 计算充值百联--需要积分、人民币值
        $blcoin_    = $params['points']*$point_blcoin_rate;// 积分抵扣掉的百联币
        if ($blcoin_ >= $params['blcoin']) {
            $need_tmb   = 0;
        } else {
            $need_tmb   = ($params['blcoin'] - $blcoin_)/$rate;
        }
        $data['need_rmb']   = $need_tmb;
        return $data;
    }
    
    /**
     * 获取充值历史记录
     */
    public function get_recharge_his($params)
    {
        // 获取数据总页数
        $table          = "bl_recharge_his";
        $where          = array('R_USERID'=>$params['uuid'],'STATUS'=>0);
//        $total_count    = $this->CI->pay_model->total_count($where,$table);
//        if (!$total_count) {
//            $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
//            return false;
//        }
//        $data['pagecount']  = ceil($total_count/$params['pagesize']);
        // 获取列表
        $options['where']   = $where;
        $options['fields']  = "IDX id,R_TYPE type,R_ORDERIDX order_id,R_GETBLCOIN blcoin,R_EXPENDRMB rmb,R_EXOENDPOINT points,R_ORDERSTATUS status,ROWTIME create_time";
        $options['order']   = "IDX DESC";
        // $options['limit']   = array('size'=>$params['pagesize'],'page'=>$params['offset']);
        $data['list']       = $this->CI->pay_model->list_data($options,$table);
        return $data;
    }
    
    /**
     * 百联币充值异步通知
     */
    public function do_order_callback($params)
    {
        $table  = "bl_order";
        $where  = array('O_ORDERNO'=>$params['id'],'STATUS'=>0);
        $fields = "IDX AS id,O_ORDERNO order_no,O_USERID uuid,O_TYPE type,O_USERID uuid,O_TOTALPRICE AS total_price,O_EXPENDRMB AS expend_rmb,O_EXOENDPOINT AS expend_points,O_GETBLCOIN get_blcoin,O_FEETYPE fee_type,O_ORDERSTATUS order_status,O_CALLBACK callback,ROWTIME order_time";
        $order_info = $this->CI->pay_model->get_one($where,$table,$fields);
        
        if (!$order_info) {
            log_message("error", "order_callback：PC百联支付回调信息,该订单未查询到".json_encode($params)."时间:".time());
            $this->CI->error_->set_error(Err_Code::ERR_ORDER_NOT_ALLOW_PAY);
            return false;
        }
        if ($order_info['order_status'] == 1) {
            return true;
        }
        $order_status   = 1;
        $info           = '';
        // 反查百联 订单状态
        $params['uuid']         = $order_info['uuid'];
        $params['id']           = $order_info['id'];
        $params['order_no']     = $order_info['order_no'];
        $params['tran_date']    = date('Ymd',strtotime($order_info['order_time']));
        $bl_order               = $this->do_order_check($params);
        $u_info                 = $this->CI->utility->get_user_info($params['uuid']);
        $this->CI->pay_model->start();
        if (!$bl_order || $bl_order['OrderAmt'] != $order_info['total_price']) {// 退款操作
            log_message("error", "order_callback：PC百联支付回调信息,执行退款操作;订单反查信息：".json_encode($bl_order).",百联OrderAmt:".$bl_order['OrderAmt'].",游戏中心total_price:".$order_info['total_price'].";时间:".time());
            $order_status       = 3;
            $info               = '该订单被退回;应付金额'.$bl_order['total_price'].",实际金额".$bl_order['OrderAmt'].".";
            if (!$bl_order) {
                $info           = '该订单被退回;订单反查无订单信息';
            }
            
            // 插入退款历史记录
            $params['order_no']     = $order_info['order_no'];
            $params['return_money'] = $order_info['expend_points'];
            $ist_data   = array(
                'R_ORDERIDX'    => $order_info['id'],
                'R_ORDERNO'     => "R".$order_info['order_no'],
                'R_USERID'      => $order_info['uuid'],
                'R_ORDERAMT'    => $order_info['total_price'],
                'R_PAYAMT'      => $bl_order['OrderAmt'],
                'R_INFO'        => $info,
                'STATUS'        => 0,
            );
            $return_id    = $this->CI->pay_model->insert_data($ist_data,'bl_refund_his');
            if (!$return_id) {
                log_message("error", "order_callback：PC百联支付回调信息,插入退款历史记录失败;订单反查信息：".json_encode($bl_order).",百联OrderAmt:".$bl_order['OrderAmt'].",游戏中心total_price:".$order_info['total_price'].";时间:".time());
                $this->CI->pay_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_INSERT_RECHARGE_HIS_FAIL);
                return false;
            }
            
            // 退款操作之后，查看用户是否使用积分，添加积分变更历史记录
            if ($order_info['expend_points']) {
                $point_data   = array(
                    'G_USERIDX'     => $params['uuid'],
                    'G_NICKNAME'    => $u_info['name'],
                    'G_TYPE'        => 0,// 类型0:增加1:减少
                    'G_SOURCE'      => 3,// 变更来源0:充值抵扣1补签消耗2:签到获得3取消订单撤销积分消耗
                    'G_POINT'       => $order_info['expend_points'],
                    'G_INFO'        => '取消订单撤销积分'.$order_info['expend_points'],
                    'STATUS'        => 0,
                );
                $point_his_id   = $this->CI->user_model->insert_data($point_data,'bl_point_his'); 
                if (!$point_his_id) {
                    log_message("error", "order_callback：PC百联支付回调信息,订单退款,积分变更记录插入失败：".  json_encode($point_data)."时间:".time());
                    $this->CI->pay_model->error();
                    $this->CI->error_->set_error(Err_Code::ERR_INSERT_POINT_CHANGE_HIS_FAIL);
                    return false;
                }
            }
            
            // 执行退款或撤销操作
            $params['returnorder_no']   = "R".$order_info['order_no'];
            if ($bl_order['PayAmt'] == $bl_order['OrderAmt']) {
                $params['type'] = 1; // 退款操作
            } else {
                $params['type'] = 2;// 撤销操作
            }
            $refund_res = $this->do_order_refund($params);
            if (!$refund_res) {
                $this->CI->pay_model->error();
                return false;
            }
        }
        
        if ($order_status == 1) {
            if ($bl_order['OrderAmt'] != $bl_order['PayAmt']) {// 未完成支付
                log_message("error", "order_callback：PC百联支付回调信息:应付金额于实付金额不一致,订单未完成,应付：".$bl_order['OrderAmt']."实付：".$bl_order['PayAmt'].";回调数据：".json_encode($params)."时间:".time());
                $order_status   = 2;
            }
        }
        
        // 修改订单状态-成功支付状态
        $fields_1['O_CALLBACK']     = 1;
        $fields_1['O_ORDERSTATUS']  = $order_status;
        $fields_1['O_INFO']         = $info;
        $fields_1['O_EXPENDRMB']    = ($bl_order['PayAmt'] - $order_info['expend_points']);// 人民币支付金额
        $upt_res    = $this->CI->pay_model->update_data($fields_1,$where,$table);
        if (!$upt_res) {
            log_message("error", "order_callback：PC百联支付回调信息,订单状态更新失败:".json_encode($fields_1).";回调订单信息：".json_encode($params)."时间:".time());
            $this->CI->pay_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_UPDATE_ORDER_FAIL);
            return false;
        }
        
        // 充值成功---->获取百联币
        if ($order_status == 1) {
            // 增加用户百联币数
            $sql    = "UPDATE bl_user SET U_BLCOIN = U_BLCOIN+".$order_info['get_blcoin']." WHERE IDX=".$order_info['uuid']." AND STATUS = 0";
            $upt_res = $this->CI->pay_model->fetch($sql,'update');
            if (!$upt_res) {
                log_message("error", "order_callback：PC百联支付回调信息,增加百联币".$order_info['get_blcoin']."失败;回调订单信息：".json_encode($params)."时间:".time());
                $this->CI->pay_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_PAY_BLCOIN_FAIL);
                return false;
            }
            // 插入订单充值历史记录
            $ist_data   = array(
                'R_ORDERIDX'    => $order_info['id'],
                'R_ORDERNO'     => $order_info['order_no'],
                'R_TYPE'        => $order_info['type'],
                'R_USERID'      => $order_info['uuid'],
                'R_TOTALPRICE'  => $order_info['total_price'],
                'R_EXPENDRMB'   => $fields_1['O_EXPENDRMB'],
                'R_EXOENDPOINT' => $order_info['expend_points'],
                'R_GETBLCOIN'   => $order_info['get_blcoin'],
                'R_FEETYPE'     => $order_info['fee_type'],
                'R_ORDERSTATUS' => 1,
                'STATUS'        => 0,
            );
            $ist_res    = $this->CI->pay_model->insert_data($ist_data,'bl_recharge_his');
            if (!$ist_res) {
                log_message("error", "order_callback：PC百联支付回调信息,订单充值历史记录:".  json_encode($ist_data)."插入失败;回调订单信息：".json_encode($params)."时间:".time());
                $this->CI->pay_model->error();
                $this->CI->error_->set_error(Err_Code::ERR_INSERT_RECHARGE_HIS_FAIL);
                return false;
            }
            
            // 百联币变更历史记录
            $bl_data    = array(
                'G_USERIDX'     => $params['uuid'],
                'G_NICKNAME'    => $u_info['name'],
                'G_TYPE'        => 0,
                'G_SOURCE'      => 1,
                'G_BLCOIN'      => $order_info['get_blcoin'],
                'G_TOTALBLCOIN' => $u_info['blcoin'] +$order_info['get_blcoin'],
                'G_INFO'        => '充值获得'.$order_info['get_blcoin']."游戏币",
                'STATUS'        => 0,
            );
            $this->load_library('game_lib');
            $ist_res    = $this->CI->game_lib->blcoin_change_his($bl_data);
            if (!$ist_res) {
                log_message("error", "order_callback：PC百联支付回调信息,百联币变更历史记录:".  json_encode($ist_res)."插入失败;".json_encode($params)."时间:".time());
                $this->CI->pay_model->error();
                return false;
            }
        }
        $this->CI->pay_model->success();
        return true;
    }
    
    /**
     * 百联接口--执行订单反查接口
     */
    public function do_order_check($params)
    {
        $this->load_library('user_lib');
        $u_info = $this->user_lib->get_register_info($params['uuid']);
        $a_key  = $this->CI->passport->get('access_token')."_".$u_info['user_id']."_".$u_info['channel'];
        $access_token   = $this->CI->cache->memcached->get($a_key);
        if (!$access_token) {
            $access_token = $this->get_access_token($u_info['sn'],$u_info['channel'],$a_key);
        }
        $token_info    = json_decode($access_token,true);
        $para['access_token']   = $token_info['accessToken'];
        $para['service_name']   = 'bl.order.core.query';
        $para['timestamp']      = time();
        $para['sn']             = $u_info['sn'];
        $para['channelId']      = $this->channel;
        // $para['passport_id']    = $u_info['passport_id'];
        $para['sign']           = $this->CI->utility->sha1_sign($para,$token_info['tokenKey']);
        if (ENVIRONMENT != 'production') {
            $url    = $this->openapi_url_test;
        } else {
            $url    = $this->openapi_url;
        }
        $url                    = $url."service.htm?openapi_params=".base64_encode(http_build_query($para));
        $data['memberId']       = $u_info['user_id'];
        $data['BusiType']       = '0001';
        $data['MerId']          = (string)$this->CI->passport->get('p_merid');
        $data['MerOrderNo']     = (string)$params['order_no'];
        $data['TranDate']       = (string)$params['tran_date'];
        $data['TranType']       = '0502';
        $data['Version']        = '20140728';
        $str                    = rtrim($this->output_sign($data),"&");
        $p_secret               = $this->CI->passport->get('p_secret');
        $key                    = strtoupper(md5($p_secret));
        $data['Signature']      = strtoupper(openssl_digest($str.$key, 'sha256'));
        $header = array(
            "Content-type: application/json", 
        );
        $content                = $this->CI->utility->post($url,json_encode($data),$header);
        $content_arr            = json_decode($content,true);
        if ($content_arr['respCode'] != '0000') {
            if ($content_arr['errorCode'] == 'BL10012') {// ACCESS_TOKEN失效
                $access_token   = $this->user_lib->get_access_token($u_info['sn'],$u_info['channel'],$a_key);
                $data           = $this->do_order_check($params);
                return $data;
            } else {
                $this->CI->error_->set_error(Err_Code::ERR_UPDATE_BLPOINT_FAIL);
                log_message('error', 'do_order_check:do_order_check'.$this->CI->input->ip_address().',反查结果失败');
                return false;
            }
        }
        return $content_arr;
    }
    
    /**
     * 取消订单支付状态
     * @param type $params
     */
    public function do_cancel_order($params)
    {
        // 判断该订单是否存在
        $table  = "bl_order";
        $where  = array('IDX'=>$params['id'],'STATUS'=>0);
        $o_info = $this->CI->pay_model->get_one($where,$table);
        if (!$o_info) {
            log_message('error', 'do_cancel_order:该订单不存在'.$this->CI->input->ip_address().',请求参数params:'.  http_build_query($params).';查询条件:'.  json_encode($where)."执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_ORDER_NOT_ALLOW_CANCEL);
            return false;
        }
        // 修改订单状态
        $this->CI->pay_model->start();
        $fields     = array('O_ORDERSTATUS'=>3);
        $upt_res    = $this->CI->pay_model->update_data($fields,$where,$table);
        if (!$upt_res) {
            log_message('error', 'do_cancel_order:订单状态修改失败'.$this->CI->input->ip_address().',请求参数params:'.  http_build_query($params).';更新字段:'.  json_encode($fields).";订单信息：".  json_encode($o_info).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->pay_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_ORDER_CANCEL_FAIL);
            return false;
        }
        // 执行取消订单操作
        // 插入退款历史记录
        $ist_data   = array(
            'R_ORDERIDX'    => $o_info['IDX'],
            'R_ORDERNO'     => "R".$o_info['O_ORDERNO'],
            'R_USERID'      => $o_info['O_USERID'],
            'R_ORDERAMT'    => $o_info['O_TOTALPRICE'],
            'R_PAYAMT'      => $o_info['O_EXPENDRMB'],
            'R_INFO'        => "用户执行取消订单，退款",
            'STATUS'        => 0,
        );
        $return_id    = $this->CI->pay_model->insert_data($ist_data,'bl_refund_his');
        if (!$return_id) {
            $this->CI->pay_model->error();
            log_message('error', 'do_cancel_order:退款订单历史记录插入失败'.$this->CI->input->ip_address().',请求参数params:'.  http_build_query($params).';插入字段:'.  json_encode($ist_data).";订单信息：".  json_encode($o_info).";执行时间：".date('Y-m-d H:i:s',time()));
            return false;
        }
        if ($o_info['O_EXOENDPOINT']) {
            // 执行退款操作
            $params['uuid']             = $o_info['O_USERID'];
            $params['id']               = $o_info['IDX'];
            $params['order_no']         = $o_info['O_ORDERNO'];
            $params['tran_date']        = date('Ymd',strtotime($o_info['ROWTIME']));
            $params['return_money']     = $o_info['O_EXOENDPOINT'];
            $params['returnorder_no']   = "R".$o_info['O_ORDERNO'];
            $params['type']             = 2;// 撤销操作
            $refund_res = $this->do_order_refund($params);
            if (!$refund_res) {
                log_message('error', 'do_cancel_order:撤销积分：退款操作执行失败'.$this->CI->input->ip_address().',请求参数params:'.  http_build_query($params).";订单信息：".  json_encode($o_info).";执行时间：".date('Y-m-d H:i:s',time()));
                $this->CI->pay_model->error();
                return false;
            }
        }
        $this->CI->pay_model->success();
        return true;
    }
    
    /**
     * 删除订单
     */
    public function do_delete_order($params)
    {
        // 判断该订单是否存在
        $table  = "bl_order";
        $where  = array('IDX'=>$params['id'],'STATUS'=>0);
        $o_info = $this->CI->pay_model->get_one($where,$table);
        if (!$o_info) {
            $this->CI->error_->set_error(Err_Code::ERR_GET_ORDER_INFO_EMPTY);
            return false;
        }
        
        if  ($o_info['O_ORDERSTATUS'] == 2 || $o_info['O_ORDERSTATUS'] == 4) {// 订单状态 0:失败1:成功2:待支付3关闭4等待支付结果
            log_message("error", "do_delete_order：该订单状态不允许删除;".$this->CI->input->ip_address().";请求参数params".  json_encode($params).";订单信息：".  json_encode($o_info).";执行时间:".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_ORDER_NOT_ALLOW_DEL);
            return false;
        }
        // 修改订单状态
        $fields     = array('STATUS'=>1);
        $upt_res    = $this->CI->pay_model->update_data($fields,$where,$table);
        if (!$upt_res) {
            log_message("error", "do_delete_order：订单状态修改失败;".$this->CI->input->ip_address().";请求参数params".  json_encode($params).";修改字段：".  json_encode($fields).";修改条件：".  json_encode($where).";执行时间:".date('Y-m-d H:i:s',time()));
            $this->CI->pay_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_ORDER_DEL_FAIL);
            return false;
        }
        
        log_message("info", "do_delete_order：订单状态修改成功;".$this->CI->input->ip_address().";请求参数params".  json_encode($params).";修改字段：".  json_encode($fields).";修改条件：".  json_encode($where).";执行时间:".date('Y-m-d H:i:s',time()));
        return true;
    }
    
    /**
     * 百联提供接口----退款接口
     * @param type $params
     * @param int $int 控制递归回调次数
     * @return boolean
     */
    public function do_order_refund($params,$int = 0)
    {
        $this->load_library('user_lib');
        $u_info = $this->user_lib->get_register_info($params['uuid']);
        $a_key  = $this->CI->passport->get('access_token')."_".$u_info['user_id']."_".$u_info['channel'];
        $access_token   = $this->CI->cache->memcached->get($a_key);
        if (!$access_token) {
            $access_token = $this->get_access_token($u_info['sn'],$u_info['channel'],$a_key);
        }
        $token_info    = json_decode($access_token,true);
        $para['access_token']   = $token_info['accessToken'];
        $para['service_name']   = 'bl.app.pay.returnPointPay';
        $para['timestamp']      = time();
        $para['sn']             = $u_info['sn'];
        $para['channelId']      = $this->channel;
        $para['sign']           = $this->CI->utility->sha1_sign($para,$token_info['tokenKey']);
        if (ENVIRONMENT != 'production') {
            $url    = $this->openapi_url_test;
        } else {
            $url    = $this->openapi_url;
        }
        $url                    = $url."service.htm?openapi_params=".base64_encode(http_build_query($para));
        $data['memberId']       = $u_info['user_id'];
        $data['MarAfterUrl']    = base_url()."pay/order_callback";
        $data['MerId']          = (string)$this->CI->passport->get('p_merid');
        $data['MerOrderNo']     = (string)$params['returnorder_no'];
        $data['OriOrderNo']     = $params['order_no'];
        $data['OriTranDate']    = $params['tran_date'];
        $data['TranType']       = '0403';
        if ($params['type'] == 1) {
            $data['RefundAmt']      = (string)$params['return_money'];
            $data['TranType']       = '0401';
        }
        $data['TranDate']       = date('Ymd',time());
        $data['TranTime']       = date('His',time());;
        $data['Version']        = '20140728';
        $data['BusiType']       = '0001';
        $str                    = rtrim($this->output_sign($data),"&");
        $p_secret               = $this->CI->passport->get('p_secret');
        $key                    = strtoupper(md5($p_secret));
        $data['Signature']      = strtoupper(openssl_digest($str.$key, 'sha256'));
        $header = array(
            "Content-type: application/json", 
        );
        $content                = $this->CI->utility->post($url,json_encode($data),$header);
        $content_arr            = json_decode($content,true);
        if ($content_arr['respCode'] == '3203') {// 订单已经撤销成功，不能重复撤销
            log_message("error", "do_order_refund：订单已经撤销成功，不能重复撤销;请求参数params".  json_encode($params).";require_data：".  json_encode($data).";url:".$url.";return_data:".$content.";执行时间:".date('Y-m-d H:i:s',time()));
            return true;
        }
        if ($content_arr['respCode'] != '0000') {
            if ($content_arr['errorCode'] == 'BL10012') {// ACCESS_TOKEN失效
                log_message('error', 'order_callback:do_order_refund'.$this->CI->input->ip_address().',积分退款失败;递归回调次数i:'.$int.',uuid:'.$params['uuid'].';URL:'.$url.";return_data:".$content);
                if ($int < 2) {
                    $access_token   = $this->user_lib->get_access_token($u_info['sn'],$u_info['channel'],$a_key);
                    $data           = $this->do_order_refund($params,$int+1);
                    return $data;
                }
                $this->CI->error_->set_error(Err_Code::ERR_POINTS_RETURN_FAIL);
                return false;
            } else {
                $this->CI->error_->set_error(Err_Code::ERR_POINTS_RETURN_FAIL);
                log_message('error', 'order_callback:do_order_refund'.$this->CI->input->ip_address().',积分退款失败;uuid:'.$params['uuid'].';URL:'.$url.";return_data:".$content);
                return false;
            }
        }
        log_message("info", "do_order_refund：订单已经撤销成功;".$this->CI->input->ip_address().";请求参数params".  json_encode($params).";require_data：".  json_encode($data).";url:".$url.";return_data:".$content.";执行时间:".date('Y-m-d H:i:s',time()));
        return $content_arr;
    }
    
    /**
     * 获取access_token
     * Token有效期为6小时，每天允许获取token数量为10次
     * 时间到期内Token信息不变，Token到期后5分钟内可以继续访问。该5分钟新老token同时有效
     * @param string $sn 设备编号
     * @param string $channel渠道id
     * @param string $key  access_token 保存的KEY
     * @return array
     */
    public function get_access_token($sn,$key = 0)
    {
        $params['grant_type']   = 'client_credentials';
        $params['appid']        = $this->CI->passport->get('bl_appid');
        $params['secret']       = $this->CI->passport->get('bl_secret');
        $params['sn']           = $sn;
        $params['timestamp']    = time();
        $params['channelId']    = $this->channel;
        $salt                   = $this->CI->passport->get('bl_salt');
        $params['sign']         = sha1($params['grant_type'].$params['appid'].$params['secret'].$params['timestamp'].$salt.$params['sn'].$params['channelId']);
        if (ENVIRONMENT != 'production') {
            $url    = $this->openapi_url_test;
        } else {
            $url    = $this->openapi_url;
        }
        $url .= "getToken.htm?openapi_params=".base64_encode(http_build_query($params));
        $header = array(
            "Content-type: application/json", 
        );
        $access_token   = $this->CI->utility->post($url,array(),$header);
        $token_info     = json_decode($access_token,true);
        if (!$token_info['accessToken']) {
            $this->CI->error_->set_error(Err_Code::ERR_GET_ACCESS_TOKEN_FAIL);
            log_message('error', 'get_access_token:'.$this->CI->input->ip_address().',获取access_token失败;url:'.$url.";return:".$access_token);
            return false;
        }
        if ($key) {
            $this->CI->cache->memcached->save($key,$access_token,$token_info['expiresIn']);
        }
        return $access_token;
    }
    
}