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

?>

<!DOCTYPE html >
<html lang="zh-CN"></html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<?php

include_once COMM_PATH ."/log/comm_logger.php";

$mid = web_help::getIntParam("mid");



/*
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
    */
            
$mid = web_help::getIntParam("mid");
$sql = "select Fmid, Fm_name,Fcontact,Fphone0,Fposition,Fcategory,Fm_desc,Fdz_detail,
        Fimg0, Fcard_img, Fcity_id from t_merchants where Fmid=".$mid;
        
$db = new comm_mysql("121.199.45.110", "dbadmin", "dbpassword", "db_dz", "utf8");

$result = $db->getRow($sql);
if($result == false)
{
    echo "Invalid user id";
    die();
}

if(isset($_POST['submit']))
{
    /*
    print_r($_FILES);
    die();
    */
    comm_logger::log_key("web_update", "try to update merchant with mid:".$mid);
    comm_logger::log_request("web_update");


    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $category = $_POST['category'];
    $desc = $_POST['desc'];
    $detail = $_POST['detail'];
    $mid = $_POST['mid'];
    $header_img = $_POST['headername'];
    $vip_img = $_POST['vipheadername'];
    
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
    
    if(isset($_FILES['header']) && $_FILES['header']['name'])
    {
        $uploader = new comm_uploader($_FILES['header']);
        $ret = $uploader->uploadImg($mid, $header_img);
        if($ret != 0)
        {
            echo "上传店铺头像图片错误, ret:".$ret;
            die();
        }
    }

    
    if(isset($_FILES['vipheader']) && $_FILES['vipheader']['name'])
    {
        $uploader2 = new comm_uploader($_FILES['vipheader']);
        $ret = $uploader2->uploadImg($mid, $vip_head_img_name);
        if($ret != 0)
        {
            echo "上传店铺会员卡图片错误, ret:".$ret;
            die();
        }        
    }

    
    $db = new comm_mysql("121.199.45.110", "dbadmin", "dbpassword", "db_dz", "utf8");
    
    $time = time(0);
    
    $sql = "update t_merchants set ".
            "Fm_name='".$db->escape($name)."'".
            ",Fcontact='".$db->escape($contact)."'".
            ",Fphone0='".$db->escape($phone)."'".
            ",Fposition='".$db->escape($address)."'".
            ",Fcategory='".$db->escape($category)."'".
            ",Fm_desc='".$db->escape($desc)."'".
            ",Fdz_detail='".$db->escape($detail)."'".
            ",Fcity_id=".$city.
            ",Fcity_cn='".$db->escape($cityname)."'".
            ",Fcity_py='".$db->escape($city_py)."'".
            ",Fcity_py_sub='".$db->escape($city_py_sub)."'".
            ",Fmodify_time=".$time." where Fmid=".$mid;
    
    
    $ret = $db->execute($sql);
    if($ret == false)
    {
        echo "修改失败：sql:[$sql]";
        die();
    }
    
    echo "修改成功";
    
    echo '<script language="javascript">alert("修改成功");window.location.href="http://121.199.45.110/dz/business/web/web_admin_update.php?mid='.$mid.'";</script>';
}
?>

<body>
	<form method="POST" enctype="multipart/form-data" action="http://121.199.45.110/dz/business/web/web_admin_update.php">
		<p><input type="hidden" name="mid" value="<?php echo $result['Fmid']; ?>"></p>
        <p><input type="hidden" name="headername" value="<?php echo $result['Fimg0']; ?>"></p>
        <p><input type="hidden" name="vipheadername" value="<?php echo $result['Fcard_img']; ?>"></p>
        
        <p>商家名称：<input name="name" value="<?php echo $result['Fm_name']; ?>"></p>
		<p>联系人：<input name="contact" value="<?php echo $result['Fcontact']; ?>"></p>
		<p>电话：<input name="phone" value="<?php echo $result['Fphone0']; ?>"></p>
		<p>地址：<input name="address" value="<?php echo $result['Fposition']; ?>"></p>
        <p>类目：<input name="category" value="<?php echo $result['Fcategory']; ?>"></p>
		<p>描述：<input name="desc" value="<?php echo $result['Fm_desc']; ?>"></p>
		<p>图片：<input type="file" name="header"></p>
        <p>会员图：<input type="file" name="vipheader"></p>
        <p>所在城市：<select name="city">
        <?php
            if($result['Fcity_id'] == 1)
            {
        ?>
                    <option value="1" selected="true">深圳</option>
                    <option value="2">广州</option>
        <?php
            }
            else
            {
        ?>
                    <option value="1">深圳</option>
                    <option value="2" selected="true">广州</option>
        <?php
            }
        ?>
                    </select></p>
		<p>权益内容：<input name="detail" value="<?php echo $result['Fdz_detail']; ?>"></p>
        <p><input name="submit" type="submit"></p>
	</form>
</body>
</html>
