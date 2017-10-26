<?php
class Search extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('search_lib');
    }
    
    /**
     * 搜索首页
     */
    public function index()
    {
        $uuid               = $this->utility->check_user_login();
        $params['uuid']     = $uuid?$uuid:0;
        $params['keywords'] = $this->request_params('keywords');
        $params['page']     = (int)$this->request_params('page');
        $params['pagesize'] = self::PAGESIZE;
        if ($params['page'] < 0) {
            $url    = 'search/index';
            $this->output_handle($url,  Err_Code::ERR_OFFSET_SET_FAIL);
        }
        //获取列表
        $params['offset']   = $this->utility->get_offset($params['page'] , $params['pagesize']);
        $data               = $this->search_lib->get_search_list($params);
        if (!$data) {
            $this->view('unfound');
        }
        //分页
        $page_link = $this->utility->pagination($data['pagecount'],$params['page'],$params['pagesize'] , 'index');
        $this->push_template_data(array('data'=>$data));
        $this->push_template_data(array('page_link'=>$page_link));
        
        $this->view('search-result');
    }
    
    /**
     * ajax请求方式
     * @return json 获取关键字列表
     */
    public function keywords_list()
    {
        $params['keywords'] = trim($this->request_params('keywords'));
        // 获取列表
        $result['code'] = 1;
        $result['list'] = $this->search_lib->get_keywords_list($params);
        echo json_encode($result);exit;
    }
    
    
    
    
    
}

