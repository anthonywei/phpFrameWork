<?php
class web_help
{
    public static function getAction()
    {
        //支持两种类型，一是 http://xxx.com/a.php?r=do
        if(isset($_GET['r']))
        {
            return $_GET['r'];
        }
        
        return "";
    }
    
    
    public static function getIntParam($name)
    {
        //先取GET参数，转成数字
        $value = "";
        if(isset($_GET["$name"]))
            $value = $_GET["$name"];
        else if(isset($_POST["$name"]))
            $value = $_POST["$name"];
        
        return ($value + 0);
    }

    public static function getStringParam($name)
    {
        //先取GET参数，转成数字
        $value = "";
        if(isset($_GET["$name"]))
            $value = $_GET["$name"];
        else if(isset($_POST["$name"]))
            $value = $_POST["$name"];
        
        return web_help::filterString($value);
    }
    
    public static function filterString($input)
    {
   	    $search = str_replace("'", "", $input);
    	$search = str_replace("\"", "", $search);
    	$search = str_replace("<", "", $search);
    	$search = str_replace(">", "", $search);
    	$search = str_replace("\\", "", $search);
    	$search = str_replace("/", "", $search);
        $search = str_replace("\n", "", $search);
        $search = str_replace("\r", "", $search);
        $search = str_replace("\t", "", $search);
        
        return $search;
    }
    
    public static function getMobileParam($mobile)
    {
        //先取GET参数，转成数字
        $value = "";
        if(isset($_GET["$name"]))
            $value = $_GET["$name"];
        else if(isset($_POST["$name"]))
            $value = $_POST["$name"];
        
        if(preg_match("/^1[0-9]{10}$/", $value)){    
            //验证通过    
            return $value;    
        }
        
        //die("手机号码格式不正确");
        return "";
    }
    
    public static function getEmailParam($email)
    {
        //先取GET参数，转成数字
        $value = "";
        if(isset($_GET["$name"]))
            $value = $_GET["$name"];
        else if(isset($_POST["$name"]))
            $value = $_POST["$name"];
        
        if(preg_match("/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i",
                $value)){    
            //验证通过    
            return $value;    
        }
        
        //die("邮箱格式不正确");
        return "";
        
    }
}

?>