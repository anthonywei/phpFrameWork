<?php

include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once COMM_PATH . "/database/comm_mysql.php";

//查询商家列表和详情
class dao_merchants
{
    var $db_;
    
    public function __construct()
    {
        $this->db_ = new comm_mysql("121.199.45.110", "dbadmin", "dbpassword", "db_dz", "utf8");
    }
    
    public function getMerchantsList($request, &$response, &$errmsg)
    {
        $sql = "select Fmid, Fm_name, Fm_desc, Fimg0, Fdz_detail from t_merchants where Fcity_id=".$request['city_id'];
        
        /*如果有搜索关键字*/
        if($request['search_name'] != "")
        {
            $sql .= " and Fm_name like '%".$this->db_->escape($request['search_name'])."%'";
        }
        
        $sql .= " order by Fmodify_time DESC limit ".$request['page_index'] * $request['page_num'].", ".$request['page_num'];
        
        
        $result = $this->db_->getAll($sql);
        if($result == false)
        {
            $errmsg = "获取商家列表发生错误，请稍后再试";
            return -1;
        }
        
        $response = $result;
        return 0;
    }
    
    public function getMerchantsDetail($request, &$response, &$errmsg)
    {
        $sql = "select Fcard_img, Fdz_detail, Fphone0, Fposition from t_merchants where Fmid=".$request['mid'];
        
        $result = $this->db_->getRow($sql);
        if($result == false)
        {
            $errmsg = "获取商家详情发生错误，请稍后再试";
            return -1;
        }
        
        $response = $result;
        return 0;
    }
}

?>