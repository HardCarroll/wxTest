<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/common.php");

class wxCommonAPI {
  private static $instance;
  private $appid;
  private $appsecret;
  private function __construct($appid, $appsecret) {
    // 私有化构造函数
    $this->appid = $appid;
    $this->appsecret = $appsecret;
  }
  private function __clone() {
    // 私有化克隆函数
  }

  // 公有静态方法，用来获取当前实例对象句柄
  public static function getInstance($appid, $appsecret) {
    if(!(self::$instance instanceof self)) {
      self::$instance = new self($appid, $appsecret);
    }
    return self::$instance;
  }

  /**
   * 获取access_token
   * @param bool $flag: 是否强制刷新access_token，默认false，即不强制刷新
   * @return string $token: access_token
   */
  public function getToken($flag = false) {
    // 获取函数调用时间
    $cur_time = time();
    // 本地文件路径
    $filepath = $_SERVER["DOCUMENT_ROOT"] . "/include/json/token.json";
    // 获取access_token官方链接
    $api_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;

    // 判断是否强制刷新(0)、本地文件是否存在(1)、是否已过期(0)
    if(!$flag and file_exists($filepath) and ($cur_time - json_decode(file_get_contents($filepath), true)["expire_time"] < 0)) {
      // 从本地文件读取access_token数据
      $tokenArray = json_decode(file_get_contents($filepath), true);
      $token = $tokenArray["access_token"];
    }
    else {
      // 官方链接获取access_token
      $retArray = json_decode(https_request($api_url), true);
      $token = $retArray["access_token"];
      // 格式化access_token数据
      $tokenArray["access_token"] = $token;
      $tokenArray["expire_time"] = $cur_time + 7200;
    }
    
    // 将access_token数据以JSON格式保存到本地文件中
    file_put_contents($filepath, json_encode($tokenArray));

    return $token;
  }

  /**
   * 获取jsapi_ticket
   * @param bool $flag: 是否强制刷新jsapi_ticket，默认false，即不强制刷新
   * @return string $ticket: jsapi_ticket
   */
  public function getTicket($flag = false) {
    // 获取函数调用时间
    $cur_time = time();
    // 本地文件路径
    $filepath = $_SERVER["DOCUMENT_ROOT"] . "/include/json/ticket.json";
    // 获取jsapi_ticket官方链接
    $api_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=" . $this->getToken();

    // 判断是否强制刷新(0)、本地文件是否存在(1)、是否已过期(0)
    if(!$flag and file_exists($filepath) and ($cur_time - json_decode(file_get_contents($filepath), true)["expire_time"] < 0)) {
      // 从本地文件读取jsapi_ticket数据
      $ticketArray = json_decode(file_get_contents($filepath), true);
      $ticket = $ticketArray["ticket"];
    }
    else {
      // 官方链接获取jsapi_ticket
      $retArray = json_decode(https_request($api_url), true);
      $ticket = $retArray["ticket"];
      // 格式化jsapi_ticket数据
      $ticketArray["ticket"] = $ticket;
      $ticketArray["expire_time"] = $cur_time + 7200;
    }
    
    // 将jsapi_ticket数据以JSON格式保存到本地文件中
    file_put_contents($filepath, json_encode($ticketArray));

    return $ticket;
  }

  /**
   * 获取数签信息数据
   */
  public function getSignData($flag = false) {
    $appId = $this->appid;
    $timestamp = time();
    $nonceStr = getRandomKey();
    $ticket = $this->getTicket($flag);

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $str = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
    $signature = sha1($str);

    $signData = array(
      'appId' => $appId,
      'timestamp' => $timestamp,
      'nonceStr' => $nonceStr,
      'signature' => $signature
    );

    return $signData;
  }

  public function getURL() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    return $url;
  }
}


  /**
   * 获取access_token
   * @param string $appid: 公众号id
   * @param string $appsecret: 公众号开发密钥
   * @param bool $flag: 是否强制刷新access_token，默认false，即不强制刷新
   * @return string $token: access_token
   */
  // function getToken($appid, $appsecret, $flag = false) {
  //   // 本地文件路径
  //   $filepath = $_SERVER["DOCUMENT_ROOT"] . "/include/json/token.json";
  //   // 获取access_token官方链接
  //   $api_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
  //   // 获取函数调用时间
  //   $cur_time = time();

  //   // 判断是否强制刷新(0)、本地文件是否存在(1)、是否已过期(0)
  //   if(!$flag and file_exists($filepath) and ($cur_time - json_decode(file_get_contents($filepath), true)["expire_time"] > 0)) {
  //     // 从本地文件读取access_token数据
  //     $tokenArray = json_decode(file_get_contents($filepath), true);
  //     $token = $tokenArray["access_token"];
  //   }
  //   else {
  //     // 官方链接获取access_token
  //     $retArray = json_decode(https_request($api_url), true);
  //     $token = $retArray["access_token"];
  //     // 格式化access_token数据
  //     $tokenArray["access_token"] = $token;
  //     $tokenArray["expire_time"] = $cur_time + 7200;
  //   }
    
  //   // 将access_token数据以JSON格式保存到本地文件中
  //   file_put_contents($filepath, json_encode($tokenArray));

  //   return $token;
  // }

  /**
   * 获取jsapi_ticket
   * @param string $token: access_token
   * @param bool $flag: 是否强制刷新jsapi_ticket，默认false，即不强制刷新
   * @return string $ticket: jsapi_ticket
   */
  // function getTicket($appid, $appsecret) {
  //   // 本地文件路径
  //   $filepath = $_SERVER["DOCUMENT_ROOT"] . "/include/json/ticket.json";
  //   // 获取jsapi_ticket官方链接
  //   $api_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=" . $token;
  //   // 获取函数调用时间
  //   $cur_time = time();

  //   // 判断本地文件是否存在(1)、是否已过期(0)
  //   if(file_exists($filepath) and ($cur_time - json_decode(file_get_contents($filepath), true)["expire_time"] >= 0)) {
  //     // 从本地文件读取jsapi_ticket数据
  //     $ticketArray = json_decode(file_get_contents($filepath), true);
  //     $ticket = $ticketArray["ticket"];
  //   }
  //   else {
  //     // 官方链接获取jsapi_ticket
  //     $retArray = json_decode(https_request($api_url), true);
  //     $ticket = $retArray["ticket"];
  //     // 格式化jsapi_ticket数据
  //     $ticketArray["ticket"] = $ticket;
  //     $ticketArray["expire_time"] = $cur_time + 7200;
  //   }
    
  //   // 将jsapi_ticket数据以JSON格式保存到本地文件中
  //   file_put_contents($filepath, json_encode($ticketArray));

  //   return $ticket;
  // }