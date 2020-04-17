<?php
header("content-type:text/html; charset:utf-8;");
//set timezone
date_default_timezone_set("Asia/Shanghai");

class wxCallbackAPI {
  private $appid;
  private $appsecret;
  private $token;

  public function __construct($appid, $appsecret, $token)
  {
    $this->appid = $appid;
    $this->appsecret = $appsecret;
    $this->token = $token;
  }

  /**
    * 用于微信公众号里填写的URL的验证，
    * 如果合格则直接将"echostr"字段原样返回
    */
  public function valid() {
    $echoStr = $_GET["echostr"];
    if ($this->checkSignature()) {
      echo($echoStr);
      exit();
    }
  }

  /**
    * 响应用户发来的消息
    */
    public function responseMsg() {
      // 获取post过来的数据，它一个XML格式的数据
      $postStr = file_get_contents("php://input");
      
      // 将得到的数据保存到log.xml中
      // Common::logger($postStr);
      
      if (!empty($postStr)) {
        // 把XML数据解析成一个对象
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        // 将消息类型分离成不同的类型
        $RX_TYPE = trim($postObj->MsgType);
        switch($RX_TYPE) {
          case "event":
            $result = $this->receiveEvent($postObj);
            break;
          case "text":
            $result = $this->receiveText($postObj);
            break;
          default :
            $result = $this->transferText($postObj, "Message type: ".$RX_TYPE);
            break;
        }
        
        // 通过此语句输出到用户端
        echo($result);
        
        // 打印输出的数据到log.xml
        // Common::logger($result, '来自公众号');
      }
      else {
        echo("");
        exit();
      }
    }
  
  /**
    * 用于验证是否是微信服务器发来的消息
    * @return bool
    */
  private function checkSignature() {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];

    $token = $this->token;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    if ($tmpStr == $signature){
        return true;
    }else {
        return false;
    }
  }

  /**
   * 响应接收事件类型消息
   */
  private function receiveEvent($object) {
    $result = "";
    switch($object->Event) {
      // 关注公众号事件
      case "subscribe":
        $result = $this->onSubscrible($object);
        break;
      // 菜单点击事件
      case "CLICK":
        $result = $this->onMenuClick($object);
        break;
      default :
        break;
    }
    return $result;
  }

  /**
   * 响应接收文本类型消息
   */
  private function receiveText($object) {
    return $this->transferText($object, "Hello");
  }

  /**
   * 用户关注事件处理函数
   * @param $object: 用户POST的XML数据转换而来
   * @return $result: 处理完成结果
   */
  private function onSubscrible($object) {
    $content = "我是黄狮虎，感谢你关注我的测试号！<a href='www.webserv.cn'>测试</a>";
    return $this->transferText($object, $content);
  }

  /**
   * 菜单点击事件处理函数
   * @param $object: 用户POST的XML数据转换而来
   * @return $result: 处理完成结果
   */
  private function onMenuClick($object) {
    $content = "MenuClick";
    return $this->transferText($object, $content);
  }

  /**
   * 格式化回复文本类型消息字符串
   */
  private function transferText($object, $content) {
    $xmlTpl = "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime><![CDATA[%s]]></CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA[%s]]></Content>
</xml>";
    
    $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
    return $result;
  }

  /**
   * 格式化回复图文类型消息字符串
   */
  private function transferNews($object, $newsArray) {
    if(!is_array($newsArray)) {
      return;
    }
    $itemTpl = "<item>
  <Title><![CDATA[%s]]></Title>
  <Description><![CDATA[%s]]></Description>
  <PicUrl><![CDATA[%s]]></PicUrl>
  <Url><![CDATA[%s]]></Url>
</item>";
    
    $itemStr = "";
    foreach($newsArray as $item) {
      $itemStr .= sprintf($itemTpl, $item["Title"], $item["Description"], $item["PicUrl"], $item["Url"]);
    }
    
    $xmlTpl = "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime><![CDATA[%s]]></CreateTime>
  <MsgType><![CDATA[news]]></MsgType>
  <ArticleCount>%s</ArticleCount>
  <Articles>$itemStr</Articles>
</xml>";
    
    $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
    return $result;
  }

  /**
   * 格式化回复音乐类型消息字符串
   */
  private function transferMusic($object, $musicArray) {
    $musicTpl = "<Music>
  <Title><![CDATA[%s]]></Title>
  <Description><![CDATA[%s]]></Description>
  <MusicUrl><![CDATA[%s]]></MusicUrl>
  <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";
    
    $musicStr = sprintf($musicTpl, $musicArray["Title"], $musicArray["Description"], $musicArray["MusicUrl"], $musicArray["HQMusicUrl"]);
    
    $xmlTpl = "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[music]]></MsgType>
  $musicStr
</xml>";
    
    $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
  }

  /**
   * 格式化回复图片类型消息字符串
   */
  private function transferImage($object, $imageArray) {
    $imageTpl = "<Image>
      <MediaId><![CDATA[%s]]></MediaId>
    </Image>";
    $imageTpl = sprintf($imageTpl, $imageArray['media_id']);
    $xmlTpl = "<xml>
      <ToUserName><![CDATA[%s]]></ToUserName>
      <FromUserName><![CDATA[%s]]></FromUserName>
      <CreateTime>%s</CreateTime>
      <MsgType><![CDATA[image]]></MsgType>
      $imageTpl
    </xml>";
    
    $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
  }

}