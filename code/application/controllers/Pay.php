<?php
class Pay extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('pay_lib');
    }
    
    /**
     * 充值首页
     */
    public function index()
    {
        // 获取可使用积分数
        $params['uuid'] = $this->utility->check_user_login();
        if ($params['uuid'] == '') {
            $this->error_->set_error(Err_Code::ERR_USER_PLEASE_DO_LOGIN);
            $this->output_handle(base_url());
        }
        // 获取用户信息
        $u_info = $this->utility->get_user_info($params['uuid']);
        $this->push_template_data(array('u_info'=>$u_info));
        // 获取用户积分
        $points_info    = $this->pay_lib->query_bl_point($params['uuid']);
        $this->push_template_data(array('points'=>$points_info['points']));
        $this->view('recharge');
    }
    
    /**
     * 下单接口
     */
    public function pay_order()
    {
        $params['uuid']     = $this->utility->check_user_login();
        $params['blcoin']   = (int)$this->request_params('blcoin');
        $params['points']   = (int)$this->request_params('points');
        if ($params['uuid'] == '' || $params['blcoin'] == '') {
            $this->error_->set_error(Err_Code::ERR_USER_PLEASE_DO_LOGIN);
            $this->output_handle(base_url());
        }
        // 生成订单，然后使用积分支付
        $order_info = $this->pay_lib->do_generate_order($params);
        if (!$order_info) {
            // 跳转到订单页面
            $this->error_->set_error(Err_Code::ERR_PAY_ORDER_FAIL);
            $this->output_handle(base_url('/pay/index'));
        }
        
        if (!$order_info['is_second']) {
            redirect(base_url().'user/order');exit;
        }
        
        // 调用百联支付页面
        $order_info['uuid'] = $params['uuid'];
        $this->pay_lib->do_pay_by_rmb($order_info);
    }
    
    /**
     * 订单已存在（二次支付）【弃用】
     */
    public function pay_again()
    {
        $params['uuid'] = (int)$this->utility->check_user_login();
        $params['id']   = (int)$this->request_params('id');
        if ($params['uuid'] == '' || $params['id'] == '') {
            $this->error_->set_error(Err_Code::ERR_USER_PLEASE_DO_LOGIN);
            $this->output_handle(base_url());
        }
        // 执行支付操作
        $result = $this->pay_lib->do_pay_again($params);
    }
    
    /**
     * 前端回调通知
     */
    public function callback_front()
    {
        redirect(base_url().'user/order');exit;
    }
    
    /**
     * 后端回调通知
     */
    public function callback_back()
    {
        // 异步通知，更改订单O_CALLBACK状态 是否回调通知1是0未
        $content        = file_get_contents("php://input");
        $para_          = json_decode($content,true);
        $para_['id']    = $para_['merOrderNo'];
        log_message("info", "order_callback：PC百联支付回调记录".json_encode($para_).time());
        $result         = $this->pay_lib->do_order_callback($para_);
        if ($result) {
            echo '{"resCode":"00100000"}';exit;
        }
        log_message("error", "order_callback：PC游戏中心回调处理失败;result:".$result.";".json_encode($para_).time());
        echo 'ERROR';exit;
    }
    
    /**
     * 取消支付订单
     */
    public function cancel_order()
    {
        // 参数处理
        $params['uuid'] = $this->uuid;
        $params['id']   = $this->request_params('id');
        if (!$params['uuid'] || !$params['id']) {
            $result['code'] = 0;
            $this->error_->set_error(Err_Code::ERR_PARA);
            $result['msg']  = $this->error_->error_msg();
            echo json_encode($result);exit;
        }
        // 执行删除操作【code=1删除成功|code=0删除失败】
        $result['code'] =  1;
        $res            = $this->pay_lib->do_cancel_order($params);
        if (!$res) {
            $result['code'] =  0;
        }
        $result['msg']  = $this->error_->set_error(Err_Code::ERR_PARA);
        echo json_encode($result);exit;
    }
    
    /**
     * 删除订单记录
     */
    public function delete_order()
    {
        // 参数处理
        $params['uuid'] = $this->uuid;
        $params['id']   = $this->request_params('id');
        if (!$params['uuid'] || !$params['id']) {
            $result['code'] = 0;
            $this->error_->set_error(Err_Code::ERR_PARA);
            $result['msg']  = $this->error_->error_msg();
            echo json_encode($result);exit;
        }
        // 执行删除操作【code=1删除成功|code=0删除失败】
        $result['code'] =  1;
        $res            = $this->pay_lib->do_delete_order($params);
        if (!$res) {
            $result['code'] =  0;
        }
        $result['msg']  = $this->error_->set_error(Err_Code::ERR_PARA);
        echo json_encode($result);exit;
    }
    
    
    /**
     * 充值历史记录
     */
    public function recharge_record()
    {
        // 参数处理
        $params['uuid']     = $this->utility->check_user_login();
        $params['pagesize'] = 3;
        $params['page']     = (int)$this->request_params('page');
        $params['offset']   = 0;
        if ($params['page']>0) {
            $params['offset'] = ($params['page']-1)*$params['pagesize'];
        }
        if (!$params['uuid']) {
            $this->error_->set_error(Err_Code::ERR_USER_PLEASE_DO_LOGIN);
            $this->output_handle(base_url());
        }
        // 获取用户信息
        $u_info = $this->utility->get_user_info($params['uuid']);
        $this->push_template_data(array('u_info'=>$u_info));
        
        // 消息列表
        $list   = $this->pay_lib->get_recharge_his($params);
        $this->push_template_data(array('list'=>$list['list']));
        $this->view('recharge_record');
    }
    
}

