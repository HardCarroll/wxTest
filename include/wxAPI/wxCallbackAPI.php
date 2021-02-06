<?php
class wxCallbackAPI {
  private static $instance;
  private $appId;
  private $appSecret;
  private $Token;
  private $appHandle;

  private function __construct($appData, $appHandle) {
    // 私有化构造函数
    $this->appId = $appData["appId"];
    $this->appSecret = $appData["appSecrect"];
    $this->Token = $appData["Token"];
    $this->appHandle = $appHandle;
  }
  // private function __construct($appId, $appSecret, $Token, $appHandle) {
  //   // 私有化构造函数
  //   $this->appId = $appId;
  //   $this->appSecret = $appSecret;
  //   $this->Token = $Token;
  //   $this->appHandle = $appHandle;
  // }
  private function clone() {
    // 私有化克隆函数
  }
  public static function getInstance($appData, $appHandle) {
    // 公有静态方法，获取实例对象
    if(!(self::$instance instanceof self)) {
      self::$instance = new self($appData, $appHandle);
    }
    return self::$instance;
  }
  // public static function getInstance($appId, $appSecret, $Token, $appHandle) {
  //   // 公有静态方法，获取实例对象
  //   if(!(self::$instance instanceof self)) {
  //     self::$instance = new self($appId, $appSecret, $Token, $appHandle);
  //   }
  //   return self::$instance;
  // }

  /**
   * 用于微信公众号里填写的URL的验证，
   * 如果合格则直接将"echostr"字段原样返回
   */
  public function valid()
  {
    $echoStr = $_GET["echostr"];
    if ($this->checkSignature()) {
      echo $echoStr;
      exit;
    }
  }

  /**
   * 用于验证是否是微信服务器发来的消息
   * @return bool
   */
  private function checkSignature()
  {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];

    $token = $this->Token;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    if ($tmpStr == $signature) {
      return true;
    }
    else {
      return false;
    }
  }

  /**
   * 发送文本消息
   */
  private function transmitText($object, $content) {
    $xmlTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime><![CDATA[%s]]></CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
              </xml>";
    return sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
  }
  /**
   * 发送图片消息
   */
  private function transmitImage($object, $imageData) {
    $xmlTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime><![CDATA[%s]]></CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                  <MediaId><![CDATA[%s]]></MediaId>
                </Image>
              </xml>";
    return sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $imageData["MediaId"]);
  }
  /**
   * 发送语音消息
   */
  private function transmitVoice($object, $voiceData) {
    $xmlTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime><![CDATA[%s]]></CreateTime>
                <MsgType><![CDATA[voice]]></MsgType>
                <Voice>
                  <MediaId><![CDATA[%s]]></MediaId>
                </Voice>
              </xml>";
    
    return sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $voiceData["MediaId"]);
  }
  /**
   * 发送视频消息
   */
  private function transmitVideo($object, $videoData) {
    $xmlTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime><![CDATA[%s]]></CreateTime>
                <MsgType><![CDATA[video]]></MsgType>
                <Video>
                  <MediaId><![CDATA[%s]]></MediaId>
                  <Title><![CDATA[%s]]></Title>
                  <Description><![CDATA[%s]]></Description>
                </Video>
              </xml>";
    
    return sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $videoData["MediaId"], $videoData["Title"], $videoData["Description"]);
  }
  /**
   * 发送音乐消息
   */
  private function transmitMusic($object, $musicData) {
    $xmlTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime><![CDATA[%s]]></CreateTime>
                <MsgType><![CDATA[music]]></MsgType>
                <Music>
                  <Title><![CDATA[%s]]></Title>
                  <Description><![CDATA[%s]]></Description>
                  <MusicUrl><![CDATA[%s]]></MusicUrl>
                  <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                </Music>
              </xml>";
    
    return sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $musicData["Title"], $musicData["Description"], $musicData["MusicUrl"], $musicData["HQMusicUrl"]);
  }
  /**
   * 发送图文消息
   */
  private function transmitNews($object, $newsData) {
    $xmlTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime><![CDATA[%s]]></CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>
                  <item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                  </item>
                </Articles>
              </xml>";

    return sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $newsData["Title"], $newsData["Description"], $newsData["PicUrl"], $newsData["Url"]);
  }

  /**
   * 响应类型为事件的消息
   */
  private function responseEvent($object) {
    switch($object->Event) {
      // 关注公众号事件
      case "subscribe":
        $result = $this->onSubscrible($object);
        break;
      // 菜单点击事件
      case "CLICK":
        $result = $this->onMenuClick($object);
        break;
      case "scancode_waitmsg":
        $result = $this->onScanCode($object);
        break;
      case "LOCATION":
        $result = $this->onLocation($object);
        break;
      default :
        $result = "Unknown Event";
        break;
    }
    return $result;
  }

  /**
   * 用户关注事件处理函数
   * @param $object: 用户POST的XML数据转换而来
   * @return $result: 处理完成结果
   */
  private function onSubscrible($object) {
    $openid = $object->FromUserName;
    $user_json = $this->appHandle["wxUser"]->getUserInfoAPI($this->getAppToken(), $openid);
    $user_arr = json_decode($user_json, TRUE);
    $content = "终于等到你，" . $user_arr["nickname"];
    $content .= "\n感谢你关注我的测试号！\n我是<a href='www.webserv.cn'>黄狮虎</a>";
    return $this->transmitText($object, $content);
  }

  /**
   * 菜单点击事件处理函数
   * @param $object: 用户POST的XML数据转换而来
   * @return $result: 处理完成结果
   */
  private function onMenuClick($object) {
    $content = "Menu ".$object->EventKey." Click";
    return $this->transmitText($object, $content);
  }
  private function onScanCode($object) {
    $content = $object->EventKey." ".json_encode($object->ScanCodeInfo, 320);
    return $this->transmitText($object, $content);
  }
  private function onLocation($object) {
    // $content = "您当前的位置是：\n[纬度:".$object->Latitude."]\n[经度:".$object->Longitude."]\n[精度:".$object->Precision."]";
    // $content = json_encode($object, 320);
    // return $this->transmitText($object, $content);
  }

  /**
   * 响应类型为文本的消息
   */
  private function responseText($object) {
    $keyword = trim($object->Content);
    switch($keyword) {
      case "音乐":
        $musicData = array("Title"=>"高山流水", "Description"=>"古筝独奏", "MusicUrl"=>"http://test.webserv.cn/media/music/", "HQMusicUrl"=>"http://test.webserv.cn/media/music/");
        $result = $this->transmitMusic($object, $musicData);
        break;
      case "图文":
        $newsData = array("Title" => "图文1标题", "Description" => "关于图文1的文字描述信息", "PicUrl" => "http://test.webserv.cn/media/image/back.jpg", "Url" => "https://www.webserv.cn");
        $result = $this->transmitNews($object, $newsData);
        break;
      case "whoami":
        $content = $this->appHandle["wxUser"]->getUserInfoAPI($this->appTokenAPI(), $object->FromUserName);
        $result = $this->transmitText($object, $content);
        break;
      default:
        $result = $this->transmitText($object, "Hello World");
        break;
    }

    return $result;
  }
  /**
   * 响应类型为图片的消息
   */
  private function responseImage($object) {
    $imageData = array("MediaId" => $object->MediaId);
    return $this->transmitImage($object, $imageData);
  }
  /**
   * 响应类型为语音的消息
   */
  private function responseVoice($object) {
    $voiceData = array("MediaId" => $object->MediaId);
    return $this->transmitVoice($object, $voiceData);
  }
  /**
   * 响应类型为视频的消息
   */
  private function responseVideo($object) {
    // 由于发送视频需审核，故无法立即发送原视频消息
    // $videoData = array("MediaId" => $object->MediaId, "Title" => "视频消息", "Description" => "关于此消息的文字描述");
    // return $this->transmitVideo($object, $videoData);
    return $this->transmitText($object, $object->MsgType);
  }
  /**
   * 响应类型为位置的消息
   */
  private function responseLocation($object) {
    // $content = "MsgType: " . $object->MsgType . "\nLocationX: " . $object->Location_X . "\nLocationY: " . $object->Location_Y . "\nLabel: " . $object->Label;
    $content = json_encode($object, 320);
    return $this->transmitText($object, $content);
  }

  /**
   * 响应用户消息
   */
  public function responseMsg() {
    // 获取post过来的数据，它一个XML格式的数据
    $postStr = file_get_contents("php://input");
    if(!empty($postStr)) {
      // 把XML数据解析成一个对象
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      $file = $_SERVER["DOCUMENT_ROOT"]."/logs/".$postObj->FromUserName.date("/Ymd/")."log.xml";
      save_logs($file, $postStr);
      // 将消息类型分离成不同的类型
      $MSG_TYPE = trim($postObj->MsgType);
      switch($MSG_TYPE) {
        case "event":
          $result = $this->responseEvent($postObj);
          break;
        case "text":
          $result = $this->responseText($postObj);
          break;
        case "image":
          $result = $this->responseImage($postObj);
          break;
        case "voice":
          $result = $this->responseVoice($postObj);
          break;
        case "video":
          $result = $this->responseVideo($postObj);
          break;
        case "location":
          $result = $this->responseLocation($postObj);
          break;
        default:
          $result = $this->transmitText($postObj, "Message type: " . $MSG_TYPE);
          break;
      }

      save_logs($file, $result, "公众号");
      // 通过此语句反馈至用户端
      echo $result;
    }
    else {
      // echo "hello world";
      exit;
    }
  }

  /**
   * 获取access_token
   */
  private function appTokenAPI($bRefresh = false) {
    $cur_time = time();
    $api_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appId . "&secret=" . $this->appSecret;
    $file = $_SERVER["DOCUMENT_ROOT"]."/include/json/token.json";
    if(file_exists($file)) {
      $tokenArray = json_decode(file_get_contents($file), TRUE);
      if($cur_time - $tokenArray["appToken"]["expire_time"] >= 7200) {
        $bRefresh = true;
      }
    }
    else {
      $bRefresh = true;
    }

    if($bRefresh) {
      $token = https_request($api_url);
      $newToken = json_decode($token, TRUE);
      if(isset($newToken["access_token"]) && !empty($newToken["access_token"])) {
        $access_token = $tokenArray["appToken"]["access_token"] = $newToken["access_token"];
        $tokenArray["appToken"]["expire_time"] = $newToken["expire_in"] + $cur_time;
        file_put_contents($file, json_encode($tokenArray, 320));
      }
      else {
        throw new Exception($token);
      }
    }
    else {
      $access_token = $tokenArray["appToken"]["access_token"];
    }

    return $access_token;
  }
  
  public function getAppToken($bRefresh = false) {
    try {
      return $this->appTokenAPI($bRefresh);
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  public function getHandleByName($name) {
    if(isset($name) && !empty($name)) {
      return $this->appHandle[$name];
    }
  }
  public function getHandleByIndex($index = 0) {
    return $this->appHandle[$index];
  }

  public function debug() {
    // $file = $_SERVER["DOCUMENT_ROOT"]."/logs/".date("Ymd/")."test.log";
    // $dir = dirname($file);
    // is_dir($dir) or @mkdir($dir, 0777, true);
    // echo dirname($file);
    // file_put_contents($file, "test file");
  }

}