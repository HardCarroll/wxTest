<?php
// require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/wxCallbackAPI.php");
// echo "hello";

// $appid = "wxfb56a79db24fd301";
// $appsecret = "ff1493c68e5f0f3aa4d962eb48c0c71b";
// $token = "TEST";

// $wxObject = new wxCallbackAPI($appid, $appsecret, $token);

// /**
//  * 如果有"echostr"字段，说明是一个URL验证请求，
//  * 否则是微信用户发过来的信息
//  */
// if(isset($_GET["echostr"])) {
//   $wxObject->valid();
// }
// else {
//   $wxObject->responseMsg();
// }

echo "hello world";


// require_once($_SERVER['DOCUMENT_ROOT']."/lib/lib.php");
// // Common::traceHttp();

// $wxObject = new wxCallbackAPI();

// /**
//   * 如果有"echostr"字段，说明是一个URL验证请求，
//   * 否则是微信用户发过来的信息
//   */
// if(isset($_GET["echostr"])) {
//   $wxObject->valid();
// }
// else {
//   $wxObject->responseMsg();
// }

// class wxCallbackAPI {
  
//   /**
//     * 用于微信公众号里填写的URL的验证，
//     * 如果合格则直接将"echostr"字段原样返回
//     */
//   public function valid() {
//     $echoStr = $_GET["echostr"];
//     if ($this->checkSignature()) {
//       echo($echoStr);
//       exit();
//     }
//   }
  
//   /**
//     * 用于验证是否是微信服务器发来的消息
//     * @return bool
//     */
//   private function checkSignature() {
//     $signature = $_GET["signature"];
//     $timestamp = $_GET["timestamp"];
//     $nonce = $_GET["nonce"];

//     $token = TOKEN;
//     $tmpArr = array($token, $timestamp, $nonce);
//     sort($tmpArr);
//     $tmpStr = implode($tmpArr);
//     $tmpStr = sha1($tmpStr);

//     if ($tmpStr == $signature){
//         return true;
//     }else {
//         return false;
//     }
//   }
  
//   /**
//     * 响应用户发来的消息
//     */
//   public function responseMsg() {
//     //获取post过来的数据，它一个XML格式的数据
//     $postStr = file_get_contents("php://input");
//     //将得到的数据保存到log.xml中
//     // Common::logger($postStr);
    
//     if (!empty($postStr)) {
//       //把XML数据解析成一个对象
//       $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
//       //将消息类型分离成不同的类型
//       $RX_TYPE = trim($postObj->MsgType);
//       switch($RX_TYPE) {
//         case "event":
//           $result = $this->receiveEvent($postObj);
//           break;
//         case "text":
//           $result = $this->receiveText($postObj);
//           break;
//         default :
//           $result = $this->transferText($postObj, "Message type: ".$RX_TYPE);
//           break;
//       }
      
//       //通过此语句打印到用户端
//       echo($result);
      
//       //打印输出的数据到log.xml
//       Common::logger($result, '来自公众号');
//     }
//     else {
//       echo("");
//       exit();
//     }
//   }
  
//   /**
//     * 接收事件消息
//     */
//   private function receiveEvent($object) {
//     $result = "";
//     switch($object->Event) {
//         //关注公众号事件
//       case "subscribe":
//         $result = $this->onSubscrible($object);
//         break;
//       case "CLICK":
//         $result = $this->dealClick($object);
//         break;
//       default :
//         break;
//     }
//     return $result;
//   }
  
//    /**
//     * 接收文本消息
//     * 关键词回复功能
//     */
//   private function receiveText($object) {
//     $keyword = trim($object->Content);
//     switch($keyword) {
// 			case "功能测试":
// 				$funcRecord = array();
// 				$funcRecord[] = array("Title"=>"功能测试", "Description"=>"记录功能测试方法", "PicUrl"=>"http://test.webserv.cn/images/cmd.jpg", "Url"=>"http://test.webserv.cn/testrecord.php");
// 				$result = $this->transferNews($object, $funcRecord);
// 				break;
// 			case "CMD_LIST":
// 				$cmdList = array();
// 				$cmdList[] = array("Title"=>"Command List", "Description"=>"Command List", "PicUrl"=>"http://test.webserv.cn/images/cmd.jpg", "Url"=>"http://test.webserv.cn/cmdlist.php");
// 				$result = $this->transferNews($object, $cmdList);
// 				break;
// 			case "CMD_CREATEMENU":
// 				$result = $this->transferText($object, CMenu::createMenu());
// 				break;
// 			case "CMD_QUERYMENU":
// 				$result = $this->transferText($object, CMenu::queryMenu());
// 				break;
// 			case "CMD_DELETEMENU":
// 				$result = $this->transferText($object, CMenu::deleteMenu());
// 				break;
//       case "CMD_GETREMARK":
//         $result = $this->transferText($object, UserManage::getUserInfo($object->FromUserName, "remark"));
//         break;
//       case "音乐":
//         $musicArray = array("Title"=>"Death Wing", "Description"=>"Blizzard", "MusicUrl"=>"http://test.webserv.cn/music/cataclysm.mp3", "HQMusicUrl"=>"http://test.webserv.cn/music/cataclysm.mp3");
//         $result = $this->transferMusic($object, $musicArray);
//         break;
//       case "图片":
//         $imageArray = array("type"=>"image", "media_id"=>"RH8D9516eXrf2fkZ2ivqWd1x5iSBgsPfCJo30weKXEglokU-X5JXaUVFKtidHsw8", "created_at"=>1537431015);
//         $result = $this->transferImage($object, $imageArray);
//         break;
//       case "base":
//         $appid = APPID;
//         // 授权后重定向的回调链接地址，需使用urlencode对链接进行处理。
//         $redirect_uri = urlencode("http://test.webserv.cn/test.php");
        
//         $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        
//         $content = "用户授权snsapi_base实现:<a href='$url'>单击这里体验OAuth授权</a>";
        
//         $result = $this->transferText($object, $content);
//         break;
//       case "userinfo":
//         $appid = APPID;
//         // 授权后重定向的回调链接地址，需使用urlencode对链接进行处理。
//         $redirect_uri = urlencode("http://test.webserv.cn/test.php");
        
//         $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
        
//         $content = "用户授权snsapi_userinfo实现:<a href='$url'>单击这里体验OAuth授权</a>";
        
//         $result = $this->transferText($object, $content);
//         break;
//       default :
//         $result = $this->transferText($object, "我是黄狮虎，如有疑问，请致电15388933393！");
//         break;
//     }
//     return $result;
//   }
  
//   /**
//     * 用户关注事件处理函数
//     * @param $object: 用户POST过来的XML数据转换而来的对象
//     * @return $result: 
//     */
//   private function onSubscrible($object) {
//     $data = file_get_contents("./json/subscribe.json");
//     $msg = json_decode($data, true);

//     $hour = date("G");
//     if ($hour < 6) {
//       $content = $msg["0"].UserManage::getUserInfo($object->FromUserName, "nickname")."，凌晨了，";
//     }
//     else if ($hour < 12) {
//       $content = $msg["1"].UserManage::getUserInfo($object->FromUserName, "nickname")."，上午好，";
//     }
//     else if ($hour < 18) {
//       $content = $msg["2"].UserManage::getUserInfo($object->FromUserName, "nickname")."，下午好，";
//     }
//     else {
//       $content = $msg["3"].UserManage::getUserInfo($object->FromUserName, "nickname")."，晚上好，";
//     }
//     $content .= '我是黄狮虎，感谢你关注我的测试号！';
    
//     return $this->transferText($object, $content);
//   }
  
//   /**
//     * 菜单项点击事件处理函数
//     * @param $object: 用户POST过来的XML数据转换而来的对象
//     * @return $result: 
//     */
//   private function dealClick($object) {
//     switch($object->EventKey) {
//       case "BTN_1_1":
//         $newArray = array();
//         $newArray[] = array("Title"=>"美食上新，请君品尝", "Description"=>"本周上新美食，记得来吃哟！", "PicUrl"=>"http://test.webserv.cn/images/img_001.jpg", "Url"=>"http://test.webserv.cn/sub_pages/update.php");
//         $result = $this->transferNews($object, $newArray);
//         break;
//       case "key_personal":
//         $result = $this->transferText($object, $object->EventKey);
//         break;
//       default :
//         break;
//     }
//     return $result;
//   }
  
//   /**
//     * 格式化回复文本消息字符串
//     */
//   private function transferText($object, $content) {
//     $xmlTpl = "<xml>
//   <ToUserName><![CDATA[%s]]></ToUserName>
//   <FromUserName><![CDATA[%s]]></FromUserName>
//   <CreateTime><![CDATA[%s]]></CreateTime>
//   <MsgType><![CDATA[text]]></MsgType>
//   <Content><![CDATA[%s]]></Content>
// </xml>";
    
//     $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
//     return $result;
//   }
  
//   /**
//     * 格式化回复图文消息字符串
//     */
//   private function transferNews($object, $newsArray) {
//     if(!is_array($newsArray)) {
//       return;
//     }
//     $itemTpl = "<item>
//   <Title><![CDATA[%s]]></Title>
//   <Description><![CDATA[%s]]></Description>
//   <PicUrl><![CDATA[%s]]></PicUrl>
//   <Url><![CDATA[%s]]></Url>
// </item>";
    
//     $itemStr = "";
//     foreach($newsArray as $item) {
//       $itemStr .= sprintf($itemTpl, $item["Title"], $item["Description"], $item["PicUrl"], $item["Url"]);
//     }
    
//     $xmlTpl = "<xml>
//   <ToUserName><![CDATA[%s]]></ToUserName>
//   <FromUserName><![CDATA[%s]]></FromUserName>
//   <CreateTime><![CDATA[%s]]></CreateTime>
//   <MsgType><![CDATA[news]]></MsgType>
//   <ArticleCount>%s</ArticleCount>
//   <Articles>$itemStr</Articles>
// </xml>";
    
//     $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
//     return $result;
//   }
  
//   /**
//     * 格式化回复音乐消息的字符串
//     */
//   private function transferMusic($object, $musicArray) {
//     $musicTpl = "<Music>
//   <Title><![CDATA[%s]]></Title>
//   <Description><![CDATA[%s]]></Description>
//   <MusicUrl><![CDATA[%s]]></MusicUrl>
//   <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
// </Music>";
    
//     $musicStr = sprintf($musicTpl, $musicArray["Title"], $musicArray["Description"], $musicArray["MusicUrl"], $musicArray["HQMusicUrl"]);
    
//     $xmlTpl = "<xml>
//   <ToUserName><![CDATA[%s]]></ToUserName>
//   <FromUserName><![CDATA[%s]]></FromUserName>
//   <CreateTime>%s</CreateTime>
//   <MsgType><![CDATA[music]]></MsgType>
//   $musicStr
// </xml>";
    
//     $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
//     return $result;
//   }
  
//   /**
//     * 格式化回复图片消息的字符串
//     */
//   private function transferImage($object, $imageArray) {
//     $imageTpl = "<Image>
//       <MediaId><![CDATA[%s]]></MediaId>
//     </Image>";
//     $imageTpl = sprintf($imageTpl, $imageArray['media_id']);
//     $xmlTpl = "<xml>
//       <ToUserName><![CDATA[%s]]></ToUserName>
//       <FromUserName><![CDATA[%s]]></FromUserName>
//       <CreateTime>%s</CreateTime>
//       <MsgType><![CDATA[image]]></MsgType>
//       $imageTpl
//     </xml>";
    
//     $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
//     return $result;
//   }
  
  
// }