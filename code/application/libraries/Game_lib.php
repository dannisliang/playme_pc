<?php
class Game_lib extends Base_lib {
    public $game_fields     = 'IDX AS id,G_GAMEIDX AS game_id,G_NAME name,G_FILEDIRECTORY file_directory,G_GAMETYPE game_type,G_BLCOIN blcoin,G_BLCOINCURRENT blcoin_curr,G_INFO info,G_OPERATIONINFO operation_info,G_KEYS keys,G_IMGS imgs,G_ICON icon,G_BUYNUM buy_num,(G_PLAYNUM+G_ADDVALUE) play_num,G_SHARENUM share_num,G_SHAREPLAYNUM shareplay_num,G_GAMESTAR game_star,G_GAMESTARNUM gamestar_num';
    public $game_fields_2   = 'A.IDX AS id ,A.G_GAMEIDX AS game_id,A.G_NAME name,A.G_FILEDIRECTORY file_directory,A.G_GAMETYPE game_type,A.G_BLCOIN blcoin,A.G_BLCOINCURRENT blcoin_curr,A.G_INFO info,A.G_OPERATIONINFO operation_info,A.G_KEYS `keys`,A.G_IMGS imgs,A.G_ICON icon,A.G_BUYNUM buy_num,(A.G_PLAYNUM+A.G_ADDVALUE) play_num,A.G_SHARENUM share_num,A.G_SHAREPLAYNUM shareplay_num,A.G_GAMESTAR game_star,A.G_GAMESTARNUM gamestar_num';
    public $bailu_callback = 
        array (
          'game_list' => 
          array (
            0 => 
            array (
              'gameId' => '257',
              'name' => '暗影之怒',
              'type' => '动作冒险',
              'payType' => '2',
              'screen' => '1',
              'played' => '48',
              'url' => 'http://api.egret-labs.org/v2/game/21695/257',
              'runtimeUrl' => 'http://g1.egret.extant.bearjoy.com/rt.php',
              'payCallBackUrl' => 'http://api.egret-labs.org/v2/pay/21695/257',
              'icon' => 'http://image.egret.com/game/gameIcon/0/257/icon_200.png?1467949641',
              'game_picture' => 
              array (
                0 => 'http://image.egret.com/game/gamePic/0/257/pub_0_publicity.jpg?1479783341',
                1 => 'http://image.egret.com/game/gamePic/0/257/pub_1_publicity.jpg?1479783341',
                2 => 'http://image.egret.com/game/gamePic/0/257/pub_2_publicity.jpg?1479783341',
                3 => 'http://image.egret.com/game/gamePic/0/257/pub_3_publicity.jpg?1479783341',
                4 => 'http://image.egret.com/game/gamePic/0/257/pub_4_publicity.jpg?1479783341',
              ),
              'desc' => '-  HTML5游戏巅峰之作，MMOARPG手游《暗影之怒》拉开全服战场，跨服夺宝谁与争锋！
        - 圣城争夺吹响集结号角！工会齐心开启城池攻防战，在这里，你不是一个人在战斗！
        - 神秘BOSS全民挑战！挑战世界BOSS最高能获极品红装，全民参与乐趣满满！
        - 女神助阵！完美身材颜值爆表，王者之路再不孤单寂寞！
        - 福利爆表！线上线下活动不断，豪华大奖等你来拿！',
              'shortDesc' => '史诗级暗黑风ARPG游戏！',
            ),
            1 => 
            array (
              'gameId' => '90549',
              'name' => '降魔西游',
              'type' => '角色扮演',
              'payType' => '2',
              'screen' => '1',
              'played' => '28',
              'url' => 'http://api.egret-labs.org/v2/game/21695/90549',
              'runtimeUrl' => NULL,
              'payCallBackUrl' => 'http://api.egret-labs.org/v2/pay/21695/90549',
              'icon' => 'http://image.egret.com/game/gameIcon/181/90549/icon_200.png?1469160622',
              'game_picture' => 
              array (
                0 => 'http://image.egret.com/game/gamePic/181/90549/pub_0_publicity.jpg?1469160622',
                1 => 'http://image.egret.com/game/gamePic/181/90549/pub_1_publicity.jpg?1469160622',
                2 => 'http://image.egret.com/game/gamePic/181/90549/pub_2_publicity.jpg?1469160622',
                3 => 'http://image.egret.com/game/gamePic/181/90549/pub_3_publicity.jpg?1469160622',
                4 => 'http://image.egret.com/game/gamePic/181/90549/pub_4_publicity.jpg?1469160622',
              ),
              'desc' => '七大角色同时战斗，哪咤、孙悟空、二郎神、牛魔王同台竞技，完成八十一成就，成就降魔西游之路！',
              'shortDesc' => '吃俺老孙一棒！',
            ),
            16 => 
            array (
              'gameId' => '22',
              'name' => '愚公移山',
              'type' => '挂机放置',
              'payType' => '2',
              'screen' => '1',
              'played' => '180',
              'url' => 'http://api.egret-labs.org/v2/game/21695/22',
              'runtimeUrl' => 'http://api.ygys.egret-labs.org/wbrt.php',
              'payCallBackUrl' => 'http://api.egret-labs.org/v2/pay/21695/22',
              'icon' => 'http://image.egret.com/game/gameIcon/0/150314183701_ebmkwg.png',
              'game_picture' => 
              array (
                0 => 'http://image.egret.com/game/gamePic/0/150314183705_xcjgtd.jpg',
                1 => 'http://image.egret.com/game/gamePic/0/150314183705_ygysab.png',
                2 => 'http://image.egret.com/game/gamePic/0/150314183705_ygysac.png',
                3 => 'http://image.egret.com/game/gamePic/0/150314183705_ygysad.png',
                4 => 'http://image.egret.com/game/gamePic/0/150314183705_ygysae.png',
              ),
              'desc' => '《愚公移山》这款游戏让你自虐到根本停不下来！让你丢节操到没朋友！和女神多多生子孙完成愚公移山的宏愿吧！   　',
              'shortDesc' => '和女神生子孙完成愚公移山的宏愿！',
            ),
          ),
          'error' => '',
          'code' => 0,
        );
    public function __construct() {
        parent::__construct();
        $this->CI->load->model('game_model');
    }
    
    /**
     * 获取网游列表
     */
    public function get_online_list($params)
    {
        $options['where']   = array('G_GAMETYPE'=>2,'G_CLOSEOFPC'=>0,'STATUS'=>0);
        $options['fields']  = $this->game_fields;
        $options['order']   = "(G_PLAYNUM+G_ADDVALUE) DESC";
        // 判断是否展示默认条数
        if ($params['default_count']) {
            $options['limit']   = array('page'=>0,'size'=>8);
        } else {
            $options['limit']   = array('page'=>$params['offset'],'size'=>$params['pagesize']);
            $total_count        = $this->CI->game_model->total_count($options['where'],'bl_game');
            if (!$total_count) {
                return false;
            }
            $data['pagecount']  = ceil($total_count/$params['pagesize']);
        }
        $list               = $this->CI->game_model->list_data($options,'bl_game');
        if (!$list) {
            return false;
        }
        $channel_id         = $this->CI->passport->get('bailu_channel_id');
        $appkey             = $this->CI->passport->get('bailu_appkey');
        $game_url           = $this->CI->passport->get('game_url');
        // 拼接网游图片路径、游戏路径
        foreach ($list as $k=>&$v) {
            // $v['g_icon']  = str_replace("/icon_100.png","/icon_200.png",$v['icon']);
            $v['g_icon']  = $game_url."/online/".$v['game_id']."/pc.png";
            $imgs       = explode(",", trim($v['imgs'],","));
            unset($v['imgs']);
            foreach ($imgs as $key=>$val) {
                $v['imgs'][$key]    = $game_url."/online/".$v['game_id']."/".$val;
            }
            // 获取白鹭游戏url透传参数
            $qry_str    = '';
            if ($params['uuid']) {
                $str        = $this->bailu_game_url_qru($channel_id, $appkey, $params['uuid']);
                $qry_str    = $str?$v['file_directory']."?".$str:"";
            }
            $v['file_directory']    = $qry_str;
            $v['style'] = 'right';
            if($k%2 == 0 ){
                $v['style'] = 'left';
            }
        }
        
        $data['list']   = $list;
        return $data;
    }
    
    /**
     * 拼接白鹭平台，游戏url透传参数
     * @return type
     */
    public function bailu_game_url_qru($channel_id,$appkey,$uuid)
    {
        $keyData['appId']   = $channel_id;
        $keyData['time']    = time();
        $keyData['userId']  = $uuid;
        $str                = "";
        ksort($keyData);
        reset($keyData);
        foreach ($keyData as $key=>$value) {
            $str  .=  $key ."=". $value;
        }
        $user_info              = $this->CI->utility->get_user_info($uuid);
        if (!$user_info) {
            $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
            return false;
        }
        $keyData['sign']        = md5($str.$appkey);
        $keyData['userName']    = $user_info['name'];
        $keyData['userImg']     = $user_info['image'];
        $keyData['userSex']     = $user_info['sex'];
        $qry_str = http_build_query($keyData);
        return $qry_str;
    }
    
    /**
     * 获取单机小游戏列表
     */
    public function get_common_list($params)
    {
        // 获取游戏列表数据
        $where              = "(G_GAMETYPE = 0 OR G_GAMETYPE = 1) AND G_CLOSEOFPC = 0 AND STATUS = 0";
        $options['where']   = $where;
        $options['order']   = "(G_PLAYNUM+G_ADDVALUE) DESC";
        $options['fields']  = $this->game_fields;
        $options['limit']   = array('page'=>$params['offset'],'size'=>$params['pagesize']);
        if ($params['default_count']) {
            $options['limit']   = array('page'=>0,'size'=>16);
        } else {
            $total_count    = $this->CI->game_model->total_count($where,'bl_game');
            if (!$total_count) {
                return false;
            }
            $data['pagecount']  = ceil($total_count/$params['pagesize']);
        }
        $list               = $this->CI->game_model->list_data($options,'bl_game');
        $game_url           = $this->CI->passport->get('game_url');
        // 查询用户收费游戏是否购买
        $buy_his    = array();
        if ($params['uuid']) {
            $buy_his = $this->get_buygame_his($params['uuid']);
        }
        foreach ($list as $k => &$v) {
            $imgs   = explode(",", trim($v['imgs'],","));
            unset($v['imgs']);
            foreach ($imgs as $key=>$val) {
                $v['imgs'][$key]    = $game_url.$v['file_directory'].$val;
            }
            $v['style'] = 'right';
            if($k%2 == 0 ){
                $v['style'] = 'left';
            }
            $v['icon']              = $game_url.$v['file_directory'].$v['icon'];
            $v['file_directory']    = $game_url.$v['file_directory']."/play/index.html";
            $v['buy_status']        = 0;
            if (in_array($v['id'], $buy_his)) {
                $v['buy_status']    = 1;
            }
        }
        
        $data['list']   = $list;
        return $data;
    }
    
    /**
     * 获取下载游戏列表
     */
    public function get_download_list($params)
    {
        $options['where']   = array('G_GAMETYPE'=>3,'G_CLOSEOFPC'=>0,'STATUS'=>0);
        $options['fields']  = $this->game_fields;
        $options['order']   = "(G_PLAYNUM+G_ADDVALUE) DESC";
        // 判断是否展示默认条数
        if ($params['default_count']) {
            $options['limit']   = array('page'=>0,'size'=>8);
        } else {
            $options['limit']   = array('page'=>$params['offset'],'size'=>$params['pagesize']);
            $total_count        = $this->CI->game_model->total_count($options['where'],'bl_game');
            if (!$total_count) {
                return false;
            }
            $data['pagecount']  = ceil($total_count/$params['pagesize']);
        }
        $list               = $this->CI->game_model->list_data($options,'bl_game');
        if (!$list) {
            return false;
        }
        $game_url           = $this->CI->passport->get('game_url');
        // 拼接图片路径
        foreach ($list as $k=>&$v) {
            $v['icon']  = $game_url."/downloadgame/".$v['game_id']."/pc.png";
            $imgs       = explode(",", trim($v['imgs'],","));
            unset($v['imgs']);
            foreach ($imgs as $key=>$val) {
                $v['imgs'][$key]    = $game_url."/downloadgame/".$v['game_id']."/".$val;
            }
            $v['style'] = 'right';
            if($k%2 == 0 ){
                $v['style'] = 'left';
            }
        }
        
        $data['list']   = $list;
        return $data;
    }
    
    /**
     * 获取购买游戏历史记录
     */
    public function get_buygame_his($uuid)
    {
        $table              = "bl_gamebuy";
        $options['where']   = array('B_USERIDX'=>$uuid,'STATUS'=>0);
        $options['fields']  = "B_GAMEIDX game_id";
        $list               = $this->CI->game_model->list_data($options,$table);
        $info               = array();
        if ($list) {
            $info   = array_column($list,'game_id');
        }
        return $info;
    }
    
    /**
     * 获取游戏详情
     */
    public function get_game_detail($params)
    {
        // 获取游戏列表数据
        $where              = "G_CLOSEOFPC = 0 AND STATUS = 0 AND IDX = {$params['id']}";
        $options['where']   = $where;
        $options['fields']  = $this->game_fields;
        $data               = $this->CI->game_model->get_one($options['where'] , 'bl_game' , $options['fields']);
        $game_url           = $this->CI->passport->get('game_url');
        $channel_id         = $this->CI->passport->get('bailu_channel_id');
        $appkey             = $this->CI->passport->get('bailu_appkey');
        
        // 查询用户收费游戏是否购买
        $buy_his    = array();
        if ($params['uuid']) {
            $buy_his     = $this->get_buygame_his($params['uuid']);
            $collect_his = $this->init_collec($params);
        }
        $imgs   = explode(",", trim($data['imgs'],","));
        unset($data['imgs']);
        foreach ($imgs as $key=>$val) {
            $data['imgs'][$key]    = $game_url.$data['file_directory'].$val;
            if($data['game_type'] == 2){
                $data['imgs'][$key] = $game_url.'/online/'.$data['game_id'].'/'.$val;
            }elseif($data['game_type'] == 3){
                $data['imgs'][$key] = $game_url.'/downloadgame/'.$data['game_id'].'/'.$val;
            }
        }
        if($data['game_type'] == 2){
            $data['icon']  = str_replace("/icon_100.png","/icon_200.png",$data['icon']);
            // 获取白鹭游戏url透传参数
            $qry_str = '';
            if ($params['uuid']) {
                $str        = $this->bailu_game_url_qru($channel_id, $appkey, $params['uuid']);
                $qry_str    = $str?$data['file_directory']."?".$str:"";
            }
            $data['file_directory'] = $qry_str;
        }elseif($data['game_type'] == 3){
            $data['icon']  = $game_url."/downloadgame/".$data['game_id']."/pc.png";
        } else{
            $data['icon']           = $game_url.$data['file_directory'].$data['icon'];
            $data['file_directory'] = $game_url.$data['file_directory']."/play/index.html";
        }
        $data['buy_status']     = 0;
        $data['collect_status'] = 'uncollect';
        if (in_array($data['id'], $buy_his)) {
            $data['buy_status'] = 1;
            }
        if($collect_his){
            $data['collect_status'] = 'collect';
        }
        return $data;
    }
    
    /*
     * 推荐游戏列表
     */
    public function get_recommend_game($params)
    {
        // 获取游戏列表数据
        $where              = "G_GAMETYPE !=3 AND G_CLOSEOFPC = 0 AND STATUS = 0";
        $options['where']   = $where;
        $options['order']   = "(G_PLAYNUM+G_ADDVALUE) DESC";
        $options['fields']  = $this->game_fields;
        //取出总页数
        $total_count    = $this->CI->game_model->total_count($where,'bl_game');
        if (!$total_count) {
            return false;
        }
        $data['pagecount']  = ceil($total_count/$params['pagesize']);
        $options['limit']   = array('page'=>$params['offset'],'size'=>$params['pagesize']);
        $list               = $this->CI->game_model->list_data($options,'bl_game');
        $channel_id         = $this->CI->passport->get('bailu_channel_id');
        $appkey             = $this->CI->passport->get('bailu_appkey');
        $game_url           = $this->CI->passport->get('game_url');
        //获取网游icon
        foreach ($list as $k => &$v) {
            if($v['game_type'] == 2){//白鹭游戏特殊处理
                // $v['icon']  = str_replace("/icon_100.png","/icon_200.png",$v['icon']);
                $v['icon']  = $game_url."/online/".$v['game_id']."/pc.png";
                // 获取白鹭游戏url透传参数
                $qry_str    = '';
                if ($params['uuid']) {
                    $str    = $this->bailu_game_url_qru($channel_id, $appkey, $params['uuid']);
                    $qry_str    = $str?$v['file_directory']."?".$str:"";
                }
                $v['file_directory']    = $qry_str;
            }elseif($v['game_type'] == 3){// 下载游戏
                $v['icon']  = $game_url."/downloadgame/".$v['game_id']."/pc.png";
            } else {
                $v['icon']              = $game_url.$v['file_directory'].$v['icon'];
                $v['file_directory']    = $game_url.$v['file_directory']."/play/index.html";
            }
            $v['style'] = 'right';
            if($k%2 == 0 ){
                $v['style'] = 'left';
            }
        }
        $data['list'] = $list;
        return $data;
    }
    
    /*
     * 获取白鹭游戏icon
     */
    public function get_bailu_icon()
    {
        //测试环境 使用$this->bailu_callback
        $gameList = $this->bailu_callback;
//        //请求白鹭获取列表
//        $channel_id         = $this->CI->passport->get('bailu_channel_id');
//        $gameList = $this->CI->utility->get('http://api.open.egret.com/Channel.gameList' , 'app_id='.$channel_id);
//        $gameList = json_decode($gameList ,TRUE);
          $gameList = $gameList['game_list'];
        foreach ($gameList as $k => $v)
        {
            $bailuGameList[$v['gameId']] = $v['icon'];
        }
        return $bailuGameList;
    }
    
    /**
     * 百联币变更历史记录
     */
    public function blcoin_change_his($data)
    {
        $table      = 'bl_blcoin_his';
        $ist_res    = $this->CI->game_model->insert_data($data,$table);
        if (!$ist_res) {
            return false;
        }
        return true;
    }
    
    /*
     * 购买游戏
     */
    public function buy_game($params)
    {
        $buy_data = $this->get_buygame_his($params['uuid']);
        if(in_array($params['id'], $buy_data)){
            log_message('error', "buy_game:不能重复购买;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";购买游戏id列表：".  json_encode($buy_data).";执行时间：".date('Y-m-d H:i:s',time()));
            return '不能重复购买';
        }
        //获取游戏详情和用户信息
        $game_detail = $this->get_game_detail($params);
        $user_info = $this->CI->utility->get_user_info($params['uuid']);
        if($game_detail){
            if($game_detail['blcoin_curr'] > $user_info['blcoin']){
                log_message('error', "buy_game:百联币不足无法购买;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";用户信息：".  json_encode($user_info).";游戏信息：".  json_encode($game_detail).";执行时间：".date('Y-m-d H:i:s',time()));
                return '百联币不足无法购买';
            }
            if($game_detail['game_type'] != 1) {
                log_message('error', "buy_game:只允许购买单机游戏;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";用户信息：".  json_encode($user_info).";游戏信息：".  json_encode($game_detail).";执行时间：".date('Y-m-d H:i:s',time()));
                return '无法购买此游戏';
            }
        }
        else {
            log_message('error', "buy_game:未查询到该游戏;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";用户信息：".  json_encode($user_info).";执行时间：".date('Y-m-d H:i:s',time()));
            return '游戏不存在';
        }
        //执行购买操作
        $this->CI->game_model->start();
        $data   = array(
            'B_USERIDX'         => $params['uuid'],
            'B_NICKNAME'        => $user_info['name'],
            'B_GAMEIDX'         => $params['id'],
            'B_GAMENAME'        => $game_detail['name'],
            'B_BLCOIN'          => $game_detail['blcoin'],
            'B_BLCOINCURRENT'   => $game_detail['blcoin_curr'],
            'STATUS'            => 0,
        );
        $ist_res    = $this->CI->game_model->insert_data($data,'bl_gamebuy');
        if (!$ist_res) {
            log_message('error', "buy_game:单机购买游戏记录插入失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入数据：".  json_encode($data).";游戏信息：".  json_encode($game_detail).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            return '数据库操作失败';
        }
        // 更新百联币
        $fields_2   = array('U_BLCOIN'=>$user_info['blcoin'] - $game_detail['blcoin_curr']);
        $where_2    = array('IDX'=>$params['uuid'],'STATUS'=>0);
        $table_2    = "bl_user";
        $upt_res    = $this->CI->game_model->update_data($fields_2,$where_2,$table_2);
        if (!$upt_res) {
            log_message('error', "buy_game:用户百联币更新失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";更新数据：".  json_encode($fields_2).";更新条件：".  json_encode($where_2).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            return '数据库操作失败';
        }
        
        // 插入百联币变更历史记录
        $ist_data   = array(
            'G_USERIDX'     => $params['uuid'],
            'G_NICKNAME'    => $user_info['name'],
            'G_TYPE'        => 1,
            'G_SOURCE'      => 2,
            'G_BLCOIN'      => $game_detail['blcoin_curr'],
            'G_TOTALBLCOIN' => $user_info['blcoin'] - $game_detail['blcoin_curr'],
            'G_INFO'        => '购买收费游戏消耗'.$game_detail['blcoin_curr']."游戏币",
            'STATUS'        => 0,
        );
        $ist_res    = $this->blcoin_change_his($ist_data);
        if (!$ist_res) {
            log_message('error', "buy_game:百联币变更历史记录插入失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入数据：".  json_encode($ist_data).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            return '数据库操作失败';
        }
        $this->CI->game_model->success();
        log_message('info', "buy_game:单机游戏购买成功;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";游戏信息：".  json_encode($game_detail).";执行时间：".date('Y-m-d H:i:s',time()));
        return 'success';
    }
    
    /*
     * 收藏按钮默认状态
     */
    public function init_collec($params)
    {
        $table  = "bl_gamefavorites";
        $where  = array('F_GAMEIDX'=>$params['id'],'F_USERIDX'=>$params['uuid'],'STATUS'=>0);
        $fields = "IDX";
        $exists = $this->CI->game_model->get_one($where , $table,$fields);
        return $exists;
    }

    /*
     * 收藏/取消收藏 操作
     */
    public function collect_game($params)
    {
        $exists = $this->init_collec($params);
        //如果已收藏就取消收藏
        if ($exists) {
            $this->CI->game_model->start();
            $fields_2   = array('STATUS'=>1);
            $where_2    = array('F_GAMEIDX'=>$params['id'],'F_USERIDX'=>$params['uuid'],'STATUS'=>0);
            $table_2    = "bl_gamefavorites";
            $upt_res    = $this->CI->game_model->update_data($fields_2,$where_2,$table_2);
            if (!$upt_res) {
                log_message('error', "collect_game:游戏取消收藏失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";更新字段：".  json_encode($fields_2).";更新条件:".  json_encode($where_2).";执行时间：".date('Y-m-d H:i:s',time()));
                $this->CI->game_model->error();
                return 'false|取消收藏失败';
            }
            $this->CI->game_model->success();
            return 'success|uncollect';
        }
        
        $where_2    = array('IDX'=>$params['id'],'G_CLOSE'=>0,'STATUS'=>0);
        $fields_2   = "G_NAME AS name,G_GAMETYPE AS game_type";
        $game_info  = $this->CI->game_model->get_one($where_2,'bl_game' ,$fields_2);
        if (!$game_info) {
            log_message('error', "collect_game:该游戏不存在;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";查询条件：".  json_encode($where_2).";执行时间：".date('Y-m-d H:i:s',time()));
            return 'false|没有此游戏';
        }
        $name   = $this->CI->utility->get_user_info($params['uuid'],'name');
        $data   = array(
            'F_USERIDX'     => $params['uuid'],
            'F_NICKNAME'    => $name,
            'F_GAMEIDX'     => $params['id'],
            'F_GAMENAME'    => $game_info['name'],
            'F_GAMETYPE'    => $game_info['game_type'],
            'STATUS'        => 0,
        );
        $res = $this->CI->game_model->insert_data($data,'bl_gamefavorites');
        if (!$res) {
            log_message('error', "collect_game:游戏收藏表插入失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入数据：".  json_encode($data).";执行时间：".date('Y-m-d H:i:s',time()));
            return 'false|收藏失败';
        }
        $this->CI->game_model->success();
        return 'success|collect';
    }
    
    /**
     * 执行道具购买操作
     */
    public function do_buy_prop($params)
    {
        //获取订单信息
        $table      = "bl_propbuy";
        $where      = array('IDX'=>$params['id'],'STATUS'=>0);
        $fields     = "IDX id,P_GAMEIDX AS game_id,P_GAMENO AS game_no,P_PROPIDX prop_id,P_TOTALFEE total_rmb,P_TOTALBLCOIN total_blcoin,P_BUYSTATUS buy_status,P_CALLBACK callback,P_EXT ext";
        $order_info = $this->CI->game_model->get_one($where,$table,$fields);
        // 判断订单是否未支付状态
        if($order_info['buy_status'] != 2) {
            log_message('error', "do_buy_prop:该订单不允许支付;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";订单信息：".  json_encode($order_info).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->error_->set_error(Err_Code::ERR_ORDER_NOT_ALLOW_BUY);
            $this->CI->output_json_return();
            return true;
        }
        
        $this->CI->game_model->start();
        // 1.查询用户信息-并开启行锁 （检查用户金币是否足够）
        $sql        = "SELECT IDX uuid,U_NAME name,U_BLCOIN blcoin FROM bl_user WHERE IDX = ".$params['uuid']. " AND STATUS = 0 FOR UPDATE";
        $user_info  = $this->CI->game_model->fetch($sql,'row');
        if (!$user_info) {
            log_message('error', "do_buy_prop：用户信息查询失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_DB_NO_DATA);
            return false;
        }
        if(($user_info['blcoin'] < $order_info['total_blcoin'])) {
            log_message('error', "do_buy_prop：百联币不足;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";用户信息：".  json_encode($user_info).";订单信息：".  json_encode($order_info).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_BLCOIN_NOT_ENOUGHT_FAIL);
            return false;
        }
        
        // 2.扣除百联币
        $fields_1   = array('U_BLCOIN' => $user_info['blcoin'] - $order_info['total_blcoin']);
        $where_1    = array('IDX'=>$params['uuid'],'STATUS'=>0);
        $rst        = $this->CI->game_model->update_data($fields_1,$where_1,'bl_user');
        if (!$rst) {
            log_message('error', "do_buy_prop：百联币扣除失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";更新字段：".  json_encode($fields_1).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_BUY_BLCOIN_DEDUCT_FAIL);
            return false;
        }
        // 3.修改道具购买状态
        $fields_2['P_BUYSTATUS']    = 0;
        $fields_2['P_PLATFORM']     = 2;
        $upt_res    = $this->CI->game_model->update_data($fields_2,$where,$table);
        if (!$upt_res) {
            log_message('error', "do_buy_prop：网游道具购买状态更新失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";更新字段：".  json_encode($fields_2).";更新条件：".  json_encode($where).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_BUY_PROP_FAIL);
            return false;
        }
        
        // 4.记录百联币变更历史记录
        $bl_data    = array(
            'G_USERIDX'     => $params['uuid'],
            'G_NICKNAME'    => $user_info['name'],
            'G_TYPE'        => 1,
            'G_SOURCE'      => 3,
            'G_BLCOIN'      => $order_info['total_blcoin'],
            'G_TOTALBLCOIN' => $user_info['blcoin'] - $order_info['total_blcoin'],
            'G_INFO'        => '网游充值消耗'.$order_info['total_blcoin']."游戏币",
            'STATUS'        => 0,
        );
        $ist_res    = $this->blcoin_change_his($bl_data);
        if (!$ist_res) {
            log_message('error', "do_buy_prop：百联币变更历史记录插入失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";插入失败：".  json_encode($bl_data).";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            return false;
        }
        
        //请求白鹭支付回调
        $bailu_post = array(
            'orderId'   => $order_info['id'],
            'userId'    => $params['uuid'],
            'money'     => $order_info['total_rmb'],
            'ext'       => $order_info['ext'],
            'time'      => time(),
        );
        $bailu_post['sign'] = $this->sign_for_bailu($bailu_post);
        //拼接支付回调url
        $call_back_data = $this->CI->utility->post($order_info['callback'] , $bailu_post);
        $call_back_data = json_decode($call_back_data , TRUE);
        //回调成功则修改订单状态为成功
        if($call_back_data['code'] != 0) {
            log_message('error', "do_buy_prop：白鹭第三方支付失败;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";白鹭支付参数：". http_build_query($bailu_post).";url：".$order_info['callback'].";执行时间：".date('Y-m-d H:i:s',time()));
            $this->CI->game_model->error();
            $this->CI->error_->set_error(Err_Code::ERR_BUY_PROP_FAIL);
            return false;
        }
        $this->CI->game_model->success();
        log_message('info', "do_buy_prop：网游道具支付成功;".$this->CI->input->ip_address().";请求参数:".  http_build_query($params).";白鹭支付参数：". http_build_query($bailu_post).";url：".$order_info['callback'].";执行时间：".date('Y-m-d H:i:s',time()));
        return true;
    }
    
    /**
     * 白鹭签名方式
     * @return type
     */
    public function sign_for_bailu($params)
    {
        $appkey = $this->CI->passport->get('bailu_appkey');
        $str  = "";
        ksort($params);
        reset($params);
        foreach($params as $key=>$value)
        {
            if ($key == 'sign' || $key == 'per' || $key == 'page') {
                continue;
            }
            $str  .=  $key ."=". $value;
        }
        $sign_new =  md5($str.$appkey);   
        return $sign_new;
    }
    
}