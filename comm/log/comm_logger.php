<?php


class comm_logger
{
    public static function log_run($module, $content)
    {
        $fp = fopen("/tmp/".$module.".".date("Y-m-d").".log", "a");
        
        $date = "[".date('Y-m-d H:i:s',time())."]";     //��־ʱ��
        $type = " [RUN] ";                              //��־����
        $content = $date . $type . $content;
        
        fwrite($fp, $content."\n");
        fclose($fp);
    }
    
    public static function log_request($module)
    {
        $fp = fopen("/tmp/".$module.".".date("Y-m-d").".log", "a");
        
        $date = "[".date('Y-m-d H:i:s',time())."]";     //��־ʱ��
        $type = " [RUN] ";                              //��־����
        
        $content = "Request=>";
        foreach($_REQUEST as $key=>$value)
            $content .= $key.":[".$value."] ";
            
        $content = $date . $type . $content;
        
        fwrite($fp, $content."\n");
        fclose($fp);        
    }  
    
    public static function log_key($module, $content)
    {
        $fp = fopen("/tmp/".$module.".".date("Y-m-d").".log", "a");
        
        $date = "[".date('Y-m-d H:i:s',time())."]";     //��־ʱ��
        $type = " [KEY] ";                              //��־����
        $content = $date . $type . $content;
        
        fwrite($fp, $content."\n");
        fclose($fp);
    }   
}


?>
