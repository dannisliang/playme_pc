<?php
class Test_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $sql     = "SELECT * FROM bl_active where STATUS = 0";
        $aa     = $this->fetch($sql);
        return $aa;
    }
    
    
    
}

