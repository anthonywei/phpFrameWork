<?php
session_start();
if(!isset($_SESSION['username']))
{
    echo "<script language='javascript'>window.location.href='http://121.199.45.110/dz/business/web/web_admin_login.php';</script>";
    die();
}
include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once COMM_PATH."/web_help/web_help.php";
include_once COMM_PATH."/smarty/comm_smarty.php";
include_once COMM_PATH."/fileupload/comm_fileupload.php";
include_once COMM_PATH."/database/comm_mysql.php";
include_once COMM_PATH ."/log/comm_logger.php";

$mid = web_help::getIntParam("mid");

comm_logger::log_key("web_delete", "try to delete merchant with mid:".$mid);
comm_logger::log_request("web_delete");

$db = new comm_mysql("121.199.45.110", "dbadmin", "dbpassword", "db_dz", "utf8");


$sql = "select Fimg0, Fcard_img from t_merchants where Fmid=".$mid;

$result = $db->getRow($sql);

if($result == false)
{
    echo "delete ok";
    die();
}
/**
 * 删除文件
 */
if(unlink(IMG_PATH . "/" . $result['Fimg0']))
{
    comm_logger::log_key("web_delete", "delete merchant img[".$result['Fimg0']."]");
    echo "delete head img ok <br/>";
}
    
if(unlink(IMG_PATH . "/" . $result['Fcard_img']))
{
    comm_logger::log_key("web_delete", "delete merchant img[".$result['Fcard_img']."]");
    
    echo "delete vip card img ok <br/>";
}

$sql ="delete from t_merchants where Fmid=".$mid;


$ret = $db->execute($sql);
if($ret == false)
{
    comm_logger::log_key("web_delete", "delete failed");
    echo "delete failed <br/>";
}
else
{
    comm_logger::log_key("web_delete", "delete ok");
    echo "delete ok <br/>";
}
?>