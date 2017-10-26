<?php
class Active_lib extends Base_lib {
    public function __construct() {
        parent::__construct();
        $this->CI->load->model('active_model');
    }
    
    /**
     * 获取活动列表
     */
    public function get_active_list($params)
    {
        $table      = "bl_active";
        $where      = "A_TYPE = 1 AND (A_PLATFORM = 1 OR A_PLATFORM = 3) AND STATUS = 0";
        $orderby    = "IDX DESC";
        $select     = "IDX id,A_ACTIVETYPE type,A_NAME name,A_TOPIMG2 top_img,A_IMG img,A_INFO info,A_GAMEID game_id,A_STARTDATE start_time,A_ENDDATETIME end_time";
        $sql        = "SELECT ".$select ." FROM ".$table." WHERE ".$where." ORDER BY ".$orderby;
        $list       = $this->CI->active_model->fetch($sql,'result');
        return $list;
    }
    
    /*
     * 获取单独活动详情
     */
    public function get_active_detail($id)
    {
        $table         = "bl_active";
        $where         = "IDX = $id AND STATUS = 0";
        $fields        = "IDX id,A_TYPE as type,A_ACTIVETYPE as avtive_type,A_NAME name,A_TOPIMG2 top_img,A_IMG img,A_INFO info,A_GAMEID game_id,A_STARTDATE start_time,A_ENDDATETIME end_time";
        $active_detail = $this->CI->active_model->get_one($where , $fields , $table);
        return $active_detail;
    }
}