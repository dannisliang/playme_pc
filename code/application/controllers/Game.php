<?php
class Game extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('game_lib');
    }
    
    /**
     * 人气列表
     */
    public function playnum_list()
    {
        $uuid               = $this->utility->check_user_login();
        $params['uuid']     = $uuid?$uuid:0;
        $params['page']   = (int)$this->request_params('page');
        $params['pagesize'] = self::PAGESIZE;
        if ($params['page'] < 0) {
            $url    = base_url().'game/playnum_list';
            $this->output_handle($url,3);// 3表示：直接跳转
        }
        //获取开始行
        $params['offset'] = $this->utility->get_offset($params['page'] , $params['pagesize']);
        $data   = $this->game_lib->get_recommend_game($params);
        //生成分页代码
        $page_link = $this->utility->pagination($data['pagecount'],$params['page'],$params['pagesize'] , 'playnum_list');
        $this->push_template_data(array('list'=>$data));
        $this->push_template_data(array('page_link'=>$page_link));
        $this->view('category-popular/category-popular-0');
    }
    
    /**
     * 单机小游戏
     */
    public function common_list()
    {
        $uuid               = $this->utility->check_user_login();
        $params['uuid']     = $uuid?$uuid:0;
        $params['page']     = (int)$this->request_params('page');
        $params['pagesize'] = self::PAGESIZE;
        if ($params['page'] < 0) {
            $url    = 'game/common_list';
            $this->output_handle($url,  Err_Code::ERR_OFFSET_SET_FAIL);
        }
        //获取开始行
        $params['offset'] = $this->utility->get_offset($params['page'] , $params['pagesize']);
        $data   = $this->game_lib->get_common_list($params);
        //生成分页代码
        $page_link = $this->utility->pagination($data['pagecount'],$params['page'],$params['pagesize'] , 'common_list');
        $this->push_template_data(array('list'=>$data));
        $this->push_template_data(array('page_link'=>$page_link));
        $this->view('category-small/category-small-0');
    }
    
    /**
     * 网游列表
     */
    public function online_list()
    {
        $uuid               = $this->utility->check_user_login();
        $params['uuid']     = $uuid?$uuid:0;
        $params['page']   = (int)$this->request_params('page');
        $params['pagesize'] = self::PAGESIZE;
        if ($params['page'] < 0) {
            $url    = 'game/online_list';
            $this->output_handle($url,  Err_Code::ERR_OFFSET_SET_FAIL);
        }
        //获取开始行
        $params['offset'] = $this->utility->get_offset($params['page'] , $params['pagesize']);
        $data   = $this->game_lib->get_online_list($params);
        //生成分页代码
        $page_link = $this->utility->pagination($data['pagecount'],$params['page'],$params['pagesize'] , 'online_list');
        $this->push_template_data(array('page_link'=>$page_link));
        $this->push_template_data(array('list'=>$data));
        $this->view('category-wy/category-wy-0');
    }
    
    /**
     * 下载游戏列表
     */
    public function download_list()
    {
        $uuid               = $this->utility->check_user_login();
        $params['uuid']     = $uuid?$uuid:0;
        $params['page']   = (int)$this->request_params('page');
        $params['pagesize'] = self::PAGESIZE;
        if ($params['page'] < 0) {
            $url    = 'game/download_list';
            $this->output_handle($url,  Err_Code::ERR_OFFSET_SET_FAIL);
        }
        //获取开始行
        $params['offset'] = $this->utility->get_offset($params['page'] , $params['pagesize']);
        $data   = $this->game_lib->get_download_list($params);
        //生成分页代码
        $page_link = $this->utility->pagination($data['pagecount'],$params['page'],$params['pagesize'] , 'download_list');
        $this->push_template_data(array('page_link'=>$page_link));
        $this->push_template_data(array('list'=>$data));
        $this->view('category-download/category');
    }
    
    /*
     * 游戏详情
     */
    public function detail()
    {
        $uuid               = $this->utility->check_user_login();
        $params['uuid']     = $uuid?$uuid:0;
        $params['id']       = (int)$this->request_params('id');
        $params['offset']   = 0;
        $params['pagesize'] = 10;
        $data   = $this->game_lib->get_game_detail($params);
        //获取推荐列表
        $recommend_data   = $this->game_lib->get_recommend_game($params);
        $this->push_template_data(array('recommend_data'=>$recommend_data));
        $this->push_template_data(array('data'=>$data));
        $this->view('game-detail.php');
    }
    
    /*
     * 验证登录
     */
    public function check_login() {
        if ($this->utility->check_user_login()){
            echo 'success';
        }
        else{
            echo 'false';
        }
    }
    
    /*
     * 购买游戏页面
     */
    public function buy_game_page()
    {
        //检查登录
        $uuid = $this->utility->check_user_login();
        if($uuid){
            //弹出购买页面
            $user_info = $this->utility->get_user_info($uuid);
            echo $user_info['blcoin'];
        }
        else{
            echo 'false';
        }
        return;
    }
    
    /*
     * 购买游戏操作
     */
    public function buy_game()
    {
        $params['uuid'] = $this->utility->check_user_login();
        $params['id']   = (int)$this->request_params('game_id');
        if($params['uuid']){
            if($params['id']){
                $return_str = $this->game_lib->buy_game($params);
                echo $return_str;exit;
            }
            else{
                echo '无法获取游戏编号！';exit;
            }
        }
        else{
            echo '请登录';exit;
        }
    }
    
    /*
     * 收藏/取消收藏 操作
     */
    public function collect_game()
    {
        $params['uuid'] = $this->utility->check_user_login();
        $params['id']   = (int)$this->request_params('game_id');
        if($params['uuid']){
            if($params['id']){
                $return_str = $this->game_lib->collect_game($params);
                echo $return_str;exit;
            }
            else{
                echo 'false|无法获取游戏编号！';exit;
            }
        }
        else{
            echo 'false|请登录';exit;
        }
    }
    
    
    /*
     * 前端发起购买请求（前端确认支付请求）
     */
    public function buy_prop() 
    {
        $params['uuid']     = $this->utility->check_user_login();
        $params['id']       = $this->request_params('id');
        if (!$params['uuid'] || !$params['id']) {
            $this->error_->set_error(Err_Code::ERR_USER_PLEASE_DO_LOGIN);
            $this->output_handle(base_url());
        }
        // 购买道具
        $this->game_lib->do_buy_prop($params);
        $this->output_json_return();
    }
    
    /**
     * 网游购买道具-唤起中间页面（确认购买页面）
     */
    public function buy_prop_page()
    {
        $this->view('/recharge_egret/recharge_egret');
    }
    
    
}

