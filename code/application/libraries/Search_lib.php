<?php
class Search_lib extends Base_lib {
    public function __construct() {
        parent::__construct();
        $this->CI->load->model('search_model');
    }
    
    /**
     * 获取关键字列表
     * @param type $params
     * @return type
     */
    public function get_keywords_list($params)
    {
        $table              = "bl_game";
        if ($params['keywords']) {
            $options['like']    = array('field'=>'G_NAME','match'=>$params['keywords']);
        }
        $options['where']   = array('G_CLOSE'=>0,'STATUS'=>0);
        $options['fields']  = "IDX id,G_GAMEIDX game_id,G_NAME name";
        $list   = $this->CI->search_model->list_data($options,$table);
        return $list;
    }
    
    /**
     * 获取搜索游戏列表
     */
    public function get_search_list($params)
    {
        //获取搜索总条数
        $sql            = "SELECT COUNT(IDX) num FROM bl_game WHERE G_CLOSE = 0 AND STATUS = 0 AND G_NAME LIKE '%".$params['keywords']."%'";
        $total_count    = $this->CI->search_model->fetch($sql);
        if (!$total_count['num']) {
            return false;
        }
        $data['total_count']= $total_count['num'];
        $data['pagecount']  = ceil($total_count['num']/$params['pagesize']);
        
        // 获取游戏列表数据
        $options['like']    = array('field'=>'G_NAME','match'=>$params['keywords']);
        $options['where']   = array('G_CLOSE'=>0,'STATUS'=>0);
        $options['fields']  = "IDX id,G_GAMEIDX game_id,G_NAME name,G_FILEDIRECTORY file_directory,G_GAMETYPE game_type,G_ICON icon,(G_PLAYNUM+G_ADDVALUE) popularity,G_INFO info";
        $options['limit']   = array('size'=>$params['pagesize'],'offset'=>$params['offset']);
        $options['order']   = "(G_PLAYNUM+G_ADDVALUE) DESC";
        $list               = $this->CI->search_model->list_data($options,'bl_game');
        
        // 拼接数据
        $channel_id         = $this->CI->passport->get('bailu_channel_id');
        $appkey             = $this->CI->passport->get('bailu_appkey');
        $game_url           = $this->CI->passport->get('game_url');
        foreach ($list as $k => &$v) {
            //白鹭游戏特殊处理
            if($v['game_type'] == 2){
                // 获取白鹭游戏url透传参数
                $qry_str    = '';
                if ($params['uuid']) {
                    $this->CI->load->library('game_lib');
                    $qry_str    = $this->CI->game_lib->bailu_game_url_qru($channel_id, $appkey, $params['uuid']);
                    $qry_str    = "?".$qry_str;
                }
                $v['file_directory']    = $v['file_directory'].$qry_str;
            } else {
                $v['icon'] = $game_url.$v['file_directory'].$v['icon'];
                $v['file_directory']    = $game_url.$v['file_directory']."/play/index.html";
            }
        }
        $data['list'] = $list;
        return $data;
    }
    
    
    
}