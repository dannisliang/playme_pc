<?php
class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('User_lib');
    }
    
    /**
     * 百联账户已登录状态---登录游戏中心
     */
    public function login()
    {
        $content    = file_get_contents("php://input");
        if ($content) {
            $params  = json_decode($content,true);
        } else {
            $params['channel']          = $this->request_params('channel');
            $params['user_id']          = $this->request_params('user_id');
            $params['nickname']         = $this->request_params('nickname');
            $params['mobile']           = $this->request_params('mobile');
            $params['image']            = $this->request_params('image');
            $params['session_id']       = $this->request_params('session_id');
            $params['member_token']     = $this->request_params('member_token');
            $params['passport_id']      = $this->request_params('passport_id');
            $params['sign']             = $this->request_params('sign');
        }
        
        // 校验参数
        if ($params['channel'] == "" || $params['user_id'] == "" || $params['mobile'] == "" || $params['nickname'] == ""  || $params['sign'] == ""  || $params['session_id'] == "" || $params['member_token'] == "" || $params['passport_id'] == "") {
            $this->error_->set_error(Err_Code::ERR_PARA);
            $this->output_json_return();
        }
        
        // 校验签名
        $this->utility->check_sign($params,$params['sign']);
        $params['image']    = (string)$params['image'];
        
        // 执行登录操作
        $params['sn']   = $params['user_id'];
        $u_info = $this->user_lib->do_login($params);
        if (!$u_info) {
            $this->output_json_return();
        }
        // 保存登录态
        $data['token']  = $this->gen_login_token($u_info['uuid'], $params['channel']);
        if ($data['token']) {
            $_SESSION['uuid']   = $u_info['uuid'];
        }
        $data['uuid']           = $u_info['uuid'];
        $data['callback_succ']  = base_url()."index/success_?uuid=".$u_info['uuid'];
        $data['callback_fail']  = base_url()."index/fail_";
        $this->output_json_return($data);
    }
    
    
    /**
     * 百联账号未登录---登录游戏中心
     */
    public function login_()
    {
        $params['name']     = $this->request_params('name');
        $params['passwd']   = $this->request_params('passwd');
        if (!$params['name'] || !$params['passwd']) {
            $this->output_handle(base_url(),Err_Code::ERR_PARA);
        }
        // 执行登录操作
        $u_info = $this->user_lib->do_login_bl($params);
        if (!$u_info) {
            $this->output_handle(base_url(),Err_Code::ERR_USER_LOGIN_FAIL);
        }
        $this->output_handle(base_url());
    }
    
    /**
     * 注销账户
     */
    public function logoff()
    {
        $content    = file_get_contents("php://input");
        if ($content) {
            $params  = json_decode($content,true);
        } else {
            $params['uuid']     = $this->request_params('uuid');
            $params['channel']  = $this->request_params('channel');
            $params['sign']     = $this->request_params('sign');
        }
        // 校验参数
        if ($params['channel'] == "" || $params['uuid'] == "" || $params['sign'] == "") {
            $this->error_->set_error(Err_Code::ERR_PARA);
            $this->output_json_return();
        }
        // 校验签名
        $this->utility->check_sign($params,$params['sign']);
        // 删除登录态
        $token_key  = $this->passport->get('pctoken_key');
        $key        = $params['uuid']."_".$params['channel']."_".$token_key;
        $this->delete_token($key);
        // unset($_SESSION['uuid']); 
        $this->output_json_return();
    }
    
    /**
     * 用户中心
     */
    public function user_center()
    {
        // 参数处理
        $params['uuid']     = $this->utility->check_user_login();
        $params['pagesize'] = 15;
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
        // 获取收藏列表
        $data   = $this->user_lib->favorite_list($params);
        $this->push_template_data(array('list'=>$data['list']));
        // 分页展示
        $page_link  = $this->utility->pagination($data['pagecount'],$params['page'],  $params['pagesize'], "user_center");
        $this->push_template_data(array('page_link'=>$page_link));
        $this->view('personal_center/personal_center-0');
    }
    
    /**
     * 我的订单
     */
    public function order()
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
        $list   = $this->user_lib->order_list($params);
        $this->push_template_data(array('list'=>$list['list']));
        // 分页
        $page_link      = $this->utility->pagination($list['pagecount'],$params['page'],  $params['pagesize'], "order");
        $this->push_template_data(array('page_link'=>$page_link));
        $this->view('personal_center/order');
    }
    
    
    /**
     * 常见问题
     */
    public function question()
    {
        $this->view('personal_center/question');
    }
        
    /**
     * 个人消息
     */
    public function news()
    {
        // 参数处理
        $params['uuid']     = $this->utility->check_user_login();
        $params['offset']   = (int)$this->request_params('offset');
        if (!$params['uuid']) {
            $this->error_->set_error(Err_Code::ERR_USER_PLEASE_DO_LOGIN);
            $this->output_handle(base_url());
        }
        // 获取用户信息
        $u_info = $this->utility->get_user_info($params['uuid']);
        $this->push_template_data(array('u_info'=>$u_info));
        
        // 消息列表
        $list   = $this->user_lib->news_list($params);
        $this->push_template_data(array('list'=>$list['list']));
        $this->view('personal_center/news');
    }
    
    /**
     * 删除我的消息
     */
    public function delete_news()
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
        $res            = $this->user_lib->do_delete_news($params);
        if (!$res) {
            $result['code'] =  0;
        }
        $result['msg']  = $this->error_->set_error(Err_Code::ERR_PARA);
        echo json_encode($result);exit;
    }
    
    /*
     * 兑换记录
     */
    public function record()
    {
        // 参数处理
        $params['uuid']     = $this->utility->check_user_login();
        $params['page']     = (int)$this->request_params('page');
        $params['pagesize'] = 3;
        //获取开始行
        $params['offset'] = $this->utility->get_offset($params['page'] , $params['pagesize']);
        if (!$params['uuid']) {
            $this->error_->set_error(Err_Code::ERR_USER_PLEASE_DO_LOGIN);
            $this->output_handle(base_url());
        }
        //获取玩家兑换记录
        $exchange_his = $this->user_lib->get_exchange_his($params);
        $this->push_template_data(array('list'=>$exchange_his));
        // 分页
        $page_link = $this->utility->pagination($exchange_his['num'],$params['page'],  $params['pagesize'], "record");
        $this->push_template_data(array('page_link'=>$page_link));
        $this->view('personal_center/record');
    }
    
    /**
     * 删除兑换记录
     */
    public function delete_exchange_his()
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
        $res            = $this->user_lib->delete_exchange_his($params);
        if (!$res) {
            $result['code'] =  0;
        }
        $result['msg']  = $this->error_->set_error(Err_Code::ERR_PARA);
        echo json_encode($result);exit;
    }
    
    
}
