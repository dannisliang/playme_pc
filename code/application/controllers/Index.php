<?php
class Index extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('active_lib');
        $this->load->library('game_lib');
        $this->load->library('user_lib');
        $this->load->library('exchange_lib');
    }
    
    /**
     * 外部条用登录成功之后--跳转
     */
    public function success_()
    {
        $params['uuid'] = $this->request_params('uuid');
        if (!$params['uuid']) {
            redirect(base_url());
        }
        // 获取用户信息
        $u_info = $this->utility->get_user_info($params['uuid']);
        
        // 判断用户是否登录
        $uuid   = $this->check_token($u_info['uuid'],$u_info['channel']);
        if ($uuid) {
            $_SESSION['uuid']   = $uuid;
        }
        redirect(base_url());
    }
    
    /**
     * 外部条用登录失败之后--跳转
     */
    public function fail_()
    {
        redirect(base_url());
    }
    
    /**
     * 游戏中心默认首页
     */
    public function index()
    {
        // 判断用户是否登录
        $params['default_count']    = 1;// 默认首页展示
        $uuid   = $this->utility->check_user_login();
        $this->utility->check_url('index/index');
        if ($uuid) {
            $params['uuid'] = $uuid;
        }
        // 活动列表
        $active_list    = $this->active_lib->get_active_list($params);
        if ($active_list) {
            foreach ($active_list as $k => &$v)
            {
                if($v['type'] == 1){
                    $v['click_url'] = 'detail?id='.$v['game_id'];
                }
                else{
                    $v['click_url'] = 'detail?id='.$v['game_id'];
                }
            }
            $this->push_template_data(array('active_list'=>$active_list));
        }
        
        // 热门网游
        $online_list    = $this->game_lib->get_online_list($params);
        if ($online_list['list']) {
            $this->push_template_data(array('online_list'=>$online_list['list']));
        }
        
        // 欢乐小游戏
        $common_list    = $this->game_lib->get_common_list($params);
        if ($common_list['list']) {
            $this->push_template_data(array('common_list'=>$common_list['list']));
        }
        
        // 热门兑换
        $exchange_list  = $this->exchange_lib->get_exchange_list($params);
        if ($exchange_list) {
            $this->push_template_data(array('exchange_list'=>$exchange_list));
        }
        
        // 获取我的游戏币
        $u_info  = array();
        if ($params['uuid']) {
            $u_info = $this->utility->get_user_info($params['uuid']);
            // 获取用户收藏数，最新消息数
            $u_info['favorite_num'] = $this->user_lib->get_favorite_num($params['uuid']);
            $u_info['news_num']     = $this->user_lib->get_news_num($params['uuid']);
            $this->push_template_data(array('u_info'=>$u_info));
        }
        
        $this->view('index');
    }
    
    
}

