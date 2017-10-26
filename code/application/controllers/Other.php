<?php
class Other extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function protocal()
    {
        $params['no_search']    = 1;
        $params['title']        = "用户服务协议";
        $this->push_template_data(array('params'=>$params));
        $this->view('other/protocal');
    }
    
    public function rights()
    {
        $params['no_search']    = 1;
        $params['title']        = "用户权益保障";
        $this->push_template_data(array('params'=>$params));
        $this->view('other/rights');
    }
    
    public function addiction()
    {
        $params['no_search']    = 1;
        $params['title']        = "防沉迷FAQ";
        $this->push_template_data(array('params'=>$params));
        $this->view('other/addiction');
    }
    
    public function dispute()
    {
        $params['no_search']    = 1;
        $params['title']        = "交易纠纷";
        $this->push_template_data(array('params'=>$params));
        $this->view('other/dispute');
    }
    
    /**
     * 未成年保护页面
     */
    public function noadult_protect()
    {
        $params['no_search']    = 1;
        $params['title']        = "未成年人家长监护工程";
        $this->push_template_data(array('params'=>$params));
        $this->view('non-adult_protect');
    }
}

