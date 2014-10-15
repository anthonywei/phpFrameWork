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
<style type="text/css">
.td
</style>
<body>
<?php

$sql = "select Fmid, Fm_name, Fm_desc, Fphone0, Fimg0, Fdz_detail, Fcity_id from t_merchants order by Fmodify_time DESC";
$db = new comm_mysql("121.199.45.110", "dbadmin", "dbpassword", "db_dz", "utf8");

$result = $db->getAll($sql);

?>
<b>添加一个新商家请点击<a href="http://121.199.45.110/dz/dynamic/temp_upload.html" target="_blank">&nbsp;添加&nbsp;</a></b><br />
<br />

<table>
    <tr>
        <td style="width: 80px; border-style: solid;">头像</td>
        <td style="width: 80px; border-style: solid;">名称</td>
        <td style="width: 80px; border-style: solid;">电话</td>
        <td style="width: 80px; border-style: solid;">城市</td>
        <td style="width: 200px;border-style: solid;">优惠</td>
        <td style="width: 200px;border-style: solid;">操作</td>
        
    </tr>
<?php
    for($i = 0; $i < count($result); ++$i)
    {
?>
    <tr>
    
        <td style="width: 80px;border-style: solid;"><img src="<?php echo IMG_PRE.$result[$i]['Fimg0'];?>" style="width: 30px;height:20px" /></td>
        <td style="width: 80px;border-style: solid;"><?php echo $result[$i]['Fm_name'];?></td>
        <td style="width: 80px;border-style: solid;"><?php echo $result[$i]['Fphone0'];?></td>
        <td style="width: 80px;border-style: solid;"><?php if($result[$i]['Fcity_id'] == 1) echo "深圳"; else echo "广州";?></td>
        <td style="width: 200px;border-style: solid;"><?php echo $result[$i]['Fdz_detail'];?></td>
        <td style="width: 200px;border-style: solid;">
        <a href="http://121.199.45.110/dz/business/web/web_admin_update.php?mid=<?php echo $result[$i]['Fmid'];  ?>" target="_blank">修改</a>
        <a href="http://121.199.45.110/dz/business/web/web_admin_delete.php?mid=<?php echo $result[$i]['Fmid'];  ?>" target="_blank">删除</a>
        
        </td>
        
    </tr>
    
<?php
    }
?>
</table>
</body>
</html>
