<?php
class wxMenuAPI {
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
  
  public function createAPI($token, $menu = "") {
    $file = $_SERVER["DOCUMENT_ROOT"]."/include/json/menu.json";
    $api_url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;
    if(!empty($menu)) {
      $menuArray = json_decode($menu, TRUE);
    }
    else {
      $menuArray = json_decode(file_get_contents($file), TRUE);
    }
    $msg = https_request($api_url, json_encode($menuArray["selfmenu_info"], 320));
    return $msg;
  }

  public function queryAPI($token) {
    $file = $_SERVER["DOCUMENT_ROOT"]."/include/json/menu.json";
    $api_url = "https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=".$token;
    $menu = https_request($api_url);
    file_put_contents($file, $menu);
    return $menu;
  }

  public function deleteAPI($token) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$token;
    $msg = https_request($api_url);
    return $msg;
  }
}