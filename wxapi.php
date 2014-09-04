<?php
//@author: saintli86
//@email: saintli86@gmail.com
//@wexin: gudianzhanxing

define("APPNAME", "HearthStone Card api")
define("AUTHOR", "saintli86")
define("WELCOME", "欢迎关注炉石（Hearth Stone）卡牌查询器，输入h显示帮助信息，输入卡牌名称返回卡牌信息。Enjoy it!")
define("NOTHING", "啊哦，没找到你想要的卡牌，请检查卡牌是否存在！")
define("TOKEN", "jiuci308")


//===========================================================================
//
class wechatCallbackapi
{
    private $fromUsername;
    private $toUsername;
    private $msgType;
    private $keyword;
    private $event;


    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        echo $echoStr;
        exit;
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
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }

    //微信消息处理入口
    public function responseMsg()
    {
        $this->fetchData();
        switch ($this->msgType)
        {
            case "text":
                $resultStr = $this->receiveText($postObj);
                break;
            case "event":
                $resultStr = $this->receiveEvent($postObj);
                break;
            default:
                $resultStr = "";
                break;
        }
        echo $resultStr;
    }
    
    //解析用户发送的数据
    public function getData()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'simpleXMLElement', LIBXML_NOCDATA);
            $this->msgType = trim($postObj->MsgType);
            $this->fromUsername = $postObj->FromUserName;
            $this->toUsername = $postObj->ToUserName;
            $this->keyword = trim($postObj->Content);
            $this->event = trim($postObj->Event);

        }else {
            exit;
        }
        
    }

    //事件响应
    private function receiveEvent()
    {
        $contentStr = "";
        if(!empty($this->event)){
            switch($this->event)
            {
                case "subscribe":
                    echo $this->responsePlainText(WELCOME);
                case "unsubscribe":
                    echo $this->responsePlainText(BYE);
            }
        }
    }
    
    //收到文字响应
    private function receiveText()
    {
        $conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
        mysql_select_db('app_hearthstone', $conn);
        mysql_query("set names 'utf8'");
        if(!empty($this->keyword)){
            switch(strtolower($this->keyword)){
                case "hi":
                    echo $this->responsePlainText(WELCOME);
                case "随机":
                    $sql = sprintf("select * from cards as t1 join (SELECT ROUND(RAND() * (SELECT MAX(id) FROM `cards`)) AS id) AS t2 where t1.id >= t2.id ORDER BY t1.id ASC LIMIT 1");
                    $result = mysql_fetch_object(mysql_query($sql));
                    if(!$result){
                        die('Invalid query: '.mysql_error());
                    }
                    //获取卡牌条目


    
            }
        }
    }

}



