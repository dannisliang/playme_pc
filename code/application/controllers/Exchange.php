<?php
class Exchange extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('exchange_lib');
    }
    
    /**
     * 执行兑换操作
     */
    public function exchange()
    {
        // 参数处理
        $params['uuid']     = $this->request_params('uuid');
        $params['id']       = $this->request_params('id');
        $params['mobile']   = $this->request_params('mobile');
        if (!$params['id'] || !$params['uuid'] || !$params['mobile']) {
            $result['code'] = 0;
            log_message('error', "exchange:参数错误;请求参数params:".http_build_query($params).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->error_->set_error(Err_Code::ERR_PARA);
            $result['msg']  = $this->error_->error_msg();
            echo json_encode($result);exit;
        }
        // 执行兑换操作 1:兑换失败-游戏币不足2:兑换成功3:兑换失败-其他错误
        $res    = $this->exchange_lib->do_exchange($params);
        if ($res === 'not_enough') {
            $code   = 1;
        } elseif($res == true) {
            $code   = 2;
        } else {
            $code   = 3;
        }
        $result['code'] = $code;
        $result['msg']  = $this->error_->error_msg();
        echo json_encode($result);exit;
    }
    
    
    
}

