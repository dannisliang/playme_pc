<?php
class Test extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('test_lib');
    }
    
    public function index()
    {
        $uuid   = $_REQUEST['uuid'];
        $channel    = $_REQUEST['channel'];
        $key    = $this->passport->get('pctoken_key');
        $key    = $uuid."_".$channel."_".$key;
        $info   = $this->get_token($key);
        var_dump($info);exit;
        var_dump($key);
        var_dump($_SESSION);
        $aa = $this->utility->check_user_login();
        var_dump($aa);exit;
        
    }
    
    
    
}
