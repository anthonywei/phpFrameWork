<?php

include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once COMM_PATH."/web_help/web_help.php";
include_once COMM_PATH."/smarty/comm_smarty.php";

$action = web_help::getAction();

switch ($action)
{
case "get_activity_list":
    WebGetMerchantsList();
    break;
}


/**
 *  返回商家的列表.
 */
function WebGetMerchantsList()
{
    comm_smarty::getSmarty($smarty);
    $smarty->display("temp_activity_list.html");
}



?>