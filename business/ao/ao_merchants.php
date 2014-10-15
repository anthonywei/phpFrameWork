<?php

include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once DAO_PATH."/dao_merchants.php";

//查询商家列表和详细信息
class ao_merchants
{
    public function getMerchantsList($request, &$response, &$errmsg)
    {
        $daoMerchants = new dao_merchants();
        $ret = $daoMerchants->getMerchantsList($request, $response, $errmsg);
        
        return $ret;
    }
    
    public function getMerchantsDetail($request, &$response, &$errmsg)
    {
        $daoMerchants = new dao_merchants();
        $ret = $daoMerchants->getMerchantsDetail($request, $response, $errmsg);
        
        return $ret;      
    }
}

?>