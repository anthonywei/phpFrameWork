<?php

include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once COMM_PATH."/web_help/web_help.php";
include_once COMM_PATH."/smarty/comm_smarty.php";
include_once AO_PATH."/ao_merchants.php";
include_once COMM_PATH ."/log/comm_logger.php";

$action = web_help::getAction();

switch ($action)
{
case "get_merchants_list":
    WebGetMerchantsList();
    break;
case "get_ajax_list":
    WebAjaxMerchantsList();
    break;
case "get_merchants_detail":
    WebGetMerchantsDetail();
    break;
}


/**
 *  返回商家的列表.
 */
function WebGetMerchantsList()
{    
    //获取城市的id
    $request['city_id'] = web_help::getIntParam("city");
    //获取搜索名称
    $request['search_name'] = web_help::getStringParam("search_name");
    
    $request['page_index'] = 0; //web_help::getIntParam("page_index");
    $request['page_num'] = 10;  //web_help::getIntParam("page_num");
    
    comm_logger::log_run("web_merchants_list", "Start one quest");
    comm_logger::log_request("web_merchants_list");
    
    $aoMerchants = new ao_merchants();
    
    $ret = $aoMerchants->getMerchantsList($request, $response, $errmsg);
    if($ret != 0)
    {
        //echo "<script language='javascript'>alert('Get Merchants list failed');</script>";
        //$response
        //die();
        //记录错误
    }
    
    comm_smarty::getSmarty($smarty);
    
    /*设置城市信息*/
    getCityHtml($request, $smarty);
    
    /*设置模版内容*/
    getMerchantsList($response, $smarty);
    
    $smarty->display("temp_merchants_list.html");
}

function WebGetMerchantsDetail()
{
    $request['mid'] = web_help::getIntParam("mid");
    
    $aoMerchants = new ao_merchants();
    
    comm_logger::log_run("web_merchants_detail", "Start one quest");
    comm_logger::log_request("web_merchants_detail");
    
    $ret = $aoMerchants->getMerchantsDetail($request, $response, $errmsg);
    if($ret != 0)
    {
        echo "<script language='javascript'>alert('Get Merchants detail failed');</script>";
        die();
    }
    
    $response['Fcard_img'] = IMG_PRE . $response['Fcard_img'];
    
    comm_smarty::getSmarty($smarty);
    $smarty->assign("merchants_detail", $response);
    $smarty->display("temp_merchants_detail.html");
}


function WebAjaxMerchantsList()
{
    //获取城市的id
    $request['city_id'] = web_help::getIntParam("city");
    //获取搜索名称
    $request['search_name'] = web_help::getStringParam("search_name");
    
    $request['page_index'] = web_help::getIntParam("page_index");
    $request['page_num'] = web_help::getIntParam("page_num"); 
    
    comm_logger::log_run("web_merchants_list_ajax", "Start one quest");
    comm_logger::log_request("web_merchants_list_ajax");
    
    $aoMerchants = new ao_merchants();
    $ret = $aoMerchants->getMerchantsList($request, $response, $errmsg);
    if($ret != 0)
    {
        //没有更多的记录了。
        die();
    }
    
    comm_smarty::getSmarty($smarty);
    
    /*设置模版内容*/
    getMerchantsList($response, $smarty);  
    
    $smarty->display("temp_merchants_list_list.html");
}

//私有函数...
function getCityHtml($request, $smarty)
{
    $smarty->assign("search_name", $request['search_name']);
    
    if($request['city_id'] == 1)
        $smarty->assign("city_list",   "<option value='1' selected=true>深圳</option><option value='2'>广州</option>");
    else if($request['city_id'] == 2)
        $smarty->assign("city_list",   "<option value='1'>深圳</option><option value='2' selected=true>广州</option>");
    else
        $smarty->assign("city_list",   "<option value='1' selected=true>深圳</option><option value='2'>广州</option>");
}


function getMerchantsList($response, $smarty)
{
    /*转换图片的URL*/
    $smarty->assign("merchants_list_count", count($response));
    
    for($i = 0; $i < count($response); ++ $i)
    {
        $response[$i]['Fimg0'] = IMG_PRE . $response[$i]['Fimg0'];
    }
    $smarty->assign("merchants_list", $response);
}

?>