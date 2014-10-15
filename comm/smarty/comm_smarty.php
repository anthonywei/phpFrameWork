<?php

/**
 *  这个类对smarty进行了简单的封装，方便后面的人使用
 */
include_once dirname(__FILE__).'/../../config/comm_config.php';
include_once dirname(__FILE__).'/libs/Smarty.class.php';

class comm_smarty
{
    public static function getSmarty(&$smarty)
    {
        $smarty = new Smarty();
        
        $smarty->setTemplateDir(TEMPLATE_PATH);
        $smarty->setCompileDir("/tmp/templates_c/");
        $smarty->setConfigDir("/tmp/configs/");
        $smarty->setCacheDir("/tmp/cache/");
        $smarty->caching = false;//true;
        //$smarty->cache_lifetime = 120;
        
        return 0;
    }
}

/*
comm_smarty::getSmarty($smarty);
$smarty->assign("title", "my test");
$smarty->assign("hi", "Hello");
$smarty->display("test.html");
*/



?>