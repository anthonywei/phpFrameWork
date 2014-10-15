<?php

session_start();

include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once COMM_PATH."/web_help/web_help.php";
include_once COMM_PATH."/smarty/comm_smarty.php";
include_once COMM_PATH."/fileupload/comm_fileupload.php";
include_once COMM_PATH."/database/comm_mysql.php";

if(isset($_POST['submit']))
{
    if($_POST['username'] == "admin" && $_POST['password'] == "jazz1234")
    {
        //登录成功
        $_SESSION['username'] = "admin";
        echo "<script language='javascript'>window.location.href='http://121.199.45.110/dz/business/web/web_admin.php';</script>";
    }
    else
    {
        echo "<script language='javascript'>alert('username or password not correct');window.location.href='http://121.199.45.110/dz/business/web/web_admin_login.php';</script>";
    }
}
?>

<!DOCTYPE html >
<html lang="zh-CN"></html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<form action="http://121.199.45.110/dz/business/web/web_admin_login.php" method="POST">

<table style="margin: auto;">
    <tr>
        <td style="width: 80px; ">用户名</td>
        <td style="width: 80px; "><input type="text" name="username" /></td>
    </tr>
    <tr>
        <td style="width: 80px; ">密码</td>
        <td style="width: 80px; "><input type="password" name="password" /></td>
    </tr>
    
    <tr>
        <td style="width: 80px; ">密码</td>
        <td style="width: 80px; "><input type="submit" name="submit" /></td>
    </tr>
    </tr>
</table>
</form>

</body>
</html>
