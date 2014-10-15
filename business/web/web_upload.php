<?php
session_start();
if(!isset($_SESSION['username']))
{
    echo "<script language='javascript'>window.location.href='http://121.199.45.110/dz/business/web/web_admin_login.php';</script>";
    die();
}
//上传商家资料的工作
include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once COMM_PATH."/web_help/web_help.php";
include_once COMM_PATH."/smarty/comm_smarty.php";
include_once COMM_PATH."/fileupload/comm_fileupload.php";
include_once COMM_PATH."/database/comm_mysql.php";
include_once COMM_PATH ."/log/comm_logger.php";


//简单处理即可：
header('Content-type:text/html; charset=UTF-8');
        
if(isset($_POST['submit']))
{
    comm_logger::log_key("web_upload", "Upload a new merchant");
    comm_logger::log_request("web_upload");
    
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $category = $_POST['category'];
    $desc = $_POST['desc'];
    $detail = $_POST['detail'];
    
    $city = $_POST['city'];
    if($city == 2)
    {
        $cityname = "广州";
        $city_py = "guangzhou";
        $city_py_sub = "gz";
    }
    else if($city == 1)
    {
        $cityname = "深圳";
        $city_py = "shenzhen";
        $city_py_sub = "sz";        
    }else
    {
        echo "城市选择不正确";
        die();
    }
    
    $user_id = ukey_next_id();
    
    $uploader = new comm_uploader($_FILES['header']);
    $ret = $uploader->uploadImg($user_id, $head_img_name);
    if($ret != 0)
    {
        echo "上传店铺头像图片错误, ret:".$ret;
        die();
    }
    
    $uploader2 = new comm_uploader($_FILES['vipheader']);
    $ret = $uploader2->uploadImg($user_id, $vip_head_img_name);
    if($ret != 0)
    {
        echo "上传店铺会员卡图片错误, ret:".$ret;
        die();
    }
    
    $db = new comm_mysql("121.199.45.110", "dbadmin", "dbpassword", "db_dz", "utf8");
    
    $time = time(0);
    
    $sql = "insert into t_merchants set Fmid=".$user_id.
            ",Fm_name='".$db->escape($name)."'".
            ",Fcontact='".$db->escape($contact)."'".
            ",Fphone0='".$db->escape($phone)."'".
            ",Fposition='".$db->escape($address)."'".
            ",Fcategory='".$db->escape($category)."'".
            ",Fm_desc='".$db->escape($desc)."'".
            ",Fdz_detail='".$db->escape($detail)."'".
            ",Fimg0='".$db->escape($head_img_name)."'".
            ",Fcard_img='".$db->escape($vip_head_img_name)."'".
            ",Fcity_id=".$city.
            ",Fcity_cn='".$db->escape($cityname)."'".
            ",Fcity_py='".$db->escape($city_py)."'".
            ",Fcity_py_sub='".$db->escape($city_py_sub)."'".
            ",Fadd_time=".$time.
            ",Fmodify_time=".$time;
    
    
    $ret = $db->execute($sql);
    if($ret == false)
    {
        echo "添加商铺失败：sql:[$sql]";
        die();
    }
    
    echo "添加成功";
}

echo "end";

?>