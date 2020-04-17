<?php
header("content-type:text/html; charset:utf-8;");

//定义TOKEN常量，这里的"TEST"就是在公众号里配置的TOKEN
define("TOKEN", "TEST");
define("APPID", "wxfb56a79db24fd301");
define("APPSECRET", "ff1493c68e5f0f3aa4d962eb48c0c71b");


//菜单管理类
class CMenu {
  /**
    * 创建自定义菜单
    * @return $result: 调用结果json，{"errcode":0,"errmsg":"ok"}表示成功
    */
  public static function createMenu() {
    $menujson = file_get_contents("./json/menu.json");
    $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".Common::getAccessToken();
    $result = Common::https_request($url, $menujson);
    if (json_decode($result)->errcode) {
      $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".Common::getAccessToken(true);
      $result = Common::https_request($url, $menujson);
    }
    return $result;
  }
  
  /**
    * 查询自定义菜单
    * @return $result: 返回自定议菜单的json数据
    */
  public static function queryMenu() {
    $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".Common::getAccessToken();
    $result = Common::https_request($url);
    return $result;
  }
  
  /**
    * 删除自定义菜单
    * @return $result: 调用结果json，{"errcode":0,"errmsg":"ok"}表示成功
    */
  public static function deleteMenu() {
    $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".Common::getAccessToken();
    $result = Common::https_request($url);
    return $result;
  }
  
}
//用户管理类
class UserManage {
  /**
    * 获取关注用户列表
    * @return $list: 
    */
  public static function getUserList() {
    $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".Common::getAccessToken();
    $list = Common::https_request($url);
    return $list;
  }
  
  /**
    * 获取用户信息
    * @param $openID: 该用户对应的opendID
    * @param $option: 想要获取哪一项信息，默认为所有
    * @return $ret: 用户信息
    */
  public static function getUserInfo($openID, $option = "ALL") {
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".Common::getAccessToken()."&openid=".$openID."&lang=zh_CN";
    $info = Common::https_request($url);
    $jsonObj = json_decode($info);
    if($jsonObj->errcode) {
      $ret = $jsonObj->errmsg;
    }
    else if(!strcmp("ALL", $option)) {
      $ret = $info;
    }
    else {
      $ret = $jsonObj->$option;
    }
    
    return $ret;
  }
}

//数据库操作类
class DBOperator {
//    主机名
  public $hostname;
//    数据库用户名
  public $username;
//    数据库密码
  public $password;
//    数据库名称
  public $dbname;
//    数据库连接字符串
  public $links;

//    构造函数
  function __construct($hostname, $username, $password, $dbname) {
    $this->hostname = $hostname;
    $this->username = $username;
    $this->password = $password;
    $this->dbname = $dbname;
    $this->links = mysqli_connect($hostname, $username, $password, $dbname);
    !mysqli_connect_error() or die("connect error(".mysqli_connect_errno().")".mysqli_connect_error());
  }

//    析构函数
  function __destruct() {
    $this->hostname = null;
    $this->username = null;
    $this->password = null;
    $this->dbname = null;
    $this->links->close();
    $this->links = null;
  }

  /**
  * 执行MYSQL操作
  * $sql: 要执行的SQL语句;
  * $return: 将执行结果返回
  */
  function execute($sql) {
    return mysqli_query($this->links, $sql);
  }
}


//通用方法类
class Common {
  
//  将请求的时间、远程主机地址和查询字符串输出到query.xml文件中。
  public static function traceHttp() {
    $content = date('Y-m-d H:i:s')."\n\rremote_ip：".$_SERVER["REMOTE_ADDR"]."\n\r".$_SERVER["QUERY_STRING"]."\n\r\n\r";
    $log_filename = $_SERVER['DOCUMENT_ROOT']."/xml/query.xml";
    $max_size = 500000;
    if (file_exists($log_filename) and (abs(filesize($log_filename))) > $max_size){
        unlink($log_filename);
    }
    file_put_contents($log_filename, $content, FILE_APPEND);
  }
  
//  logger()将类型、时间和post数据输出到log.xml中。
  public static function logger($log_content, $type = '来自用户') {
    $log_filename = $_SERVER['DOCUMENT_ROOT']."/xml/log.xml";
    $max_size = 500000;
    if (file_exists($log_filename) and (abs(filesize($log_filename))) > $max_size){
        unlink($log_filename);
    }
    file_put_contents($log_filename, date('Y-m-d H:i:s')." $type"."\n\r".$log_content."\n\r",FILE_APPEND);
  }
  
  // 官方API获取jsapi_ticket
  public static function getTicket() {
    $api_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".Common::getAccessToken()."&type=jsapi";
    $result = Common::https_request($api_url);
    return json_decode($result)->ticket;
  }
  
  public static function getJsapiTicket($flag = false) {
    // 判断本地是否已有ticket文件，没有就先获取ticket并保存在本地
    $filepath = $_SERVER["DOCUMENT_ROOT"]."/json/ticket.json";
    if (!file_exists($filepath) || $flag) {
      // 创建ticket数据数组
      $dataArray = array();
      $dataArray["jsapi_ticket"] = Common::getTicket();
      $dataArray["last_time"] = time();
      $dataArray["time_string"] = date("Y-m-d G:i:s");
      
      //将数据转换成json字符串形式
      $jsonStr = json_encode($dataArray);
      //保存到本地文件中
      file_put_contents($filepath, $jsonStr);
    }
    
    //从本地文件读取json数据
    $ticketData = file_get_contents($filepath);
    $ticketJson = json_decode($ticketData);
    
    $ticket = $ticketJson->jsapi_ticket;
    $last_time = $ticketJson->last_time;
    $cur_time = time();
    //在失效前20分钟的时候刷新并保存在本地
    if ($cur_time - $last_time >= 6000) {
      $dataArray["jsapi_ticket"] = $ticket = Common::getTicket();
      $dataArray["last_time"] = $cur_time;
      $dataArray["time_string"] = date("Y-m-d G:i:s");
      
      //将数据转换成json字符串形式
      $jsonStr = json_encode($dataArray);
      //保存到本地文件中
      file_put_contents($filepath, $jsonStr);
    }
    
    return $ticket;
  }
  
  // 官方API获取access_token
  public static function getToken() {
    $appid = APPID;
    $appsecret = APPSECRET;
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    $result = Common::https_request($url);
    return json_decode($result)->access_token;
  }
  
  /**
    * 从本地文件中获取access_token
    * @return $token: access_token
    */
  public static function getAccessToken($flag = false) {
    // 判断本地是否已有token文件，没有就先获取token并保存在本地
    $filepath = $_SERVER['DOCUMENT_ROOT']."/json/token.json";
    if(!file_exists($filepath) || $flag) {
      // 创建token数据数组
      $dataArray = array();
      $dataArray["access_token"] = Common::getToken();
      $dataArray["last_time"] = time();
      $dataArray["time_string"] = date("Y-m-d G:i:s");
      //将数据转换成json字符串形式
      $jsonStr = json_encode($dataArray);
      //保存到本地文件中
      file_put_contents($filepath, $jsonStr);
    }
    
    //从本地文件读取json数据
    $tokenData = file_get_contents($filepath);
    $tokenJson = json_decode($tokenData);
    
    $token = $tokenJson->access_token;
    $last_time = $tokenJson->last_time;
    $cur_time = time();
    //在失效前20分钟的时候刷新并保存在本地
    if ($cur_time - $last_time >= 6000) {
      $dataArray["access_token"] = $token = Common::getToken();
      $dataArray["last_time"] = $cur_time;
      $dataArray["time_string"] = date("Y-m-d G:i:s");
      
      //将数据转换成json字符串形式
      $jsonStr = json_encode($dataArray);
      //保存到本地文件中
      file_put_contents($filepath, $jsonStr);
    }
    
    return $token;
  }
  
  
  /**
    * 根据指定字符池及长度生成随机字符串
    * @param $str_pol：随机字符池，默认为"ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"
    * @param $str_len：要生成的随机字符串长度，默认为16个字符
    * @return $str_key：返回生成的随机字符串
    */
  public static function getRandomKey($str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz", $str_len = 16) {
    $str_key = "";
    for($i = 0; $i < $str_len; $i++)
    {
      $str_key .= $str_pol[mt_rand(0, strlen($str_pol)-1)];//生成php随机数
    }
    return $str_key;
  }
  
  /**
    * 发送请求
    * @param $url：地址
    * @param $data：发送的数据
    * @return $output：请求返回的结果
    */
    public static function https_request($url, $data = null) {
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
      if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      }
      $output = curl_exec($curl);
      curl_close($curl);
      return $output;
    }
  
}