<?php
class wxMaterialAPI {
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

  public function batchgetMaterialAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$token;
    return https_request($api_url, $data);
  }
  public function getMaterialCountAPI($token) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=".$token;
    return https_request($api_url);
  }
}