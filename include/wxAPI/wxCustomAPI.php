<?php
class wxCustomAPI {
  private static $instance;
  private function __construct() {
    // 私有化构造函数
  }
  private function clone() {
    // 私有化克隆函数
  }
  public static function getInstance() {
    // 公有静态方法，获取实例对象
    if(!(self::$instance instanceof self)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * 添加客服
   */
  public function addCustomAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/customservice/kfaccount/add?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }
  /**
   * 获取客服列表
   */
  public function getCustomListAPI($token) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=".$token;
    $list = https_request($api_url);
    return $list;
  }
  /**
   * 客服接口-发消息
   * 
   * http请求方式: POST
   */
  public function sendMessageAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }
}