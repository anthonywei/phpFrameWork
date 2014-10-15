<?php
/**
  * wechat php test
  */

include_once dirname(__FILE__).'/config/comm_config.php';
include_once COMM_PATH ."/log/comm_logger.php";


comm_logger::log_request("web_index");
//define your token 
define("TOKEN", "58eea01c3617046502779c6961f1a497");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

//接受消息

        

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        if(!$this->checkSignature()){
            comm_logger::log_run("web_index", "checkSignature failed");
        	exit;
        }
        
        $this->responseMsg();
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        
        comm_logger::log_run("web_index", "poststr:[".$postStr."]");

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $msgType = $postObj->MsgType;
                
                $contentStr = "您的留言已收到。客官请稍候，小卡看到留言一定回复。点击下方按钮可查看合作商家和最新的活动，每天都有更新哦。";
                
                if($msgType == "event")
                {
                    $event = $postObj->Event;
                    
                    if($event == "subscribe")
                    {
                        //首次关注
                        $contentStr = "欢迎关注工卡优惠！为腾讯员工提供各种工卡打折信息和优惠。出示员工工卡即可享受各种腾讯专属工卡特权。点击下方按钮可查看合作商家和最新的活动，每天都有更新哦。";
                    }
                    else if($event == "CLICK")
                    {
                        //点击
                        $EventKey = $postObj->EventKey;
                        if($EventKey == "ACTIVITY_SUB_KEY_1")
                        {
                            //分享有礼
                            //$contentStr = "欢迎点击推荐有奖，功能开发中，请等待。";
                            $this->sendClickMsg_button_key1($fromUsername, $toUsername);
                            
                        }
                        else if($EventKey == "ACTIVITY_SUB_KEY_2")
                        {
                            //中奖名单
                            $cu_time = time(0);
                            if($cu_time >= 1383667200) //时间大于2013-11-05 00:00:00之前都只显示文字
                                $this->sendClickMsg_button_key2($fromUsername, $toUsername);
                            else
                                $contentStr = "您好，活动刚刚开始，明天11月6日中午12点会公布11月5日中奖名单";
                        }
                    }
                }
                
                if($contentStr == "")
                    die();
                
                
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";  
                                       
                
                /**
                 * 回复消息
                 */
                $msgType = "text";
               	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
               	echo $resultStr;


        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        
        $timestamp = $_GET["timestamp"];
        
        $nonce = $_GET["nonce"];	
        
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
    
    
    private function sendClickMsg_button_key1($fromUsername, $toUsername)
    {
        die();
        
        $textTpl = "
        <xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>1</ArticleCount>
        <Articles>
        <item>
        <Title><![CDATA[腾讯员工必看工卡特权！转发朋友圈赢50元话费。]]></Title> 
        <Description><![CDATA[工卡优惠腾讯人专属工卡特权，点击右上角进行关注]]></Description>
        <PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/oMLHibcr5OcPbqQmAvuHyk3AoGLiaN5IXVBFcL8DPoIVWyY30E0dBJsxiaQH37mS04pzZ2HyU62yOr12acC8SY0NA/0]]></PicUrl>
        <Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA5NDA5NzMwNA==&appmsgid=10000001&itemidx=1&sign=353a4fbb7e8b88ac82438754bd71ede6#wechat_redirect]]></Url>
        </item>
        </Articles>
        </xml>";
        
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(0));
        
        echo $resultStr;
        die();
    }
    

    private function sendClickMsg_button_key2($fromUsername, $toUsername)
    {
        die();
        
        $textTpl = "
        <xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>1</ArticleCount>
        <Articles>
        <item>
        <Title><![CDATA[分享有礼中奖名单公布]]></Title> 
        <Description><![CDATA[每天这里都会更新往期中奖名单。请中奖者在公众后台及时联系我们，进行兑奖。（11月15日前）]]></Description>
        <PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/oMLHibcr5OcPbqQmAvuHyk3AoGLiaN5IXV4tHwrDpITibvt5tVu1eCyv6BmYKniacX4clljecm1fAYS7zlCn2QDvIw/0]]></PicUrl>
        <Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA5NDA5NzMwNA==&appmsgid=10000004&itemidx=1&sign=862ed8805f462e6504aba47c20c19274#wechat_redirect]]></Url>
        </item>
        </Articles>
        </xml>";
        
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(0));
        
        echo $resultStr;
        die();
    }
}


?>