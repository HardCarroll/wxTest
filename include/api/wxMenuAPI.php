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
  
  /**
   * 添加菜单
   * http请求方式：POST
   */
  public function createMenuAPI($token, $menu = "") {
    $file = $_SERVER["DOCUMENT_ROOT"]."/include/json/menu.json";
    $api_url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;

    if(empty($menu)) {
      $menu = file_get_contents($file);
    }
    $msg = https_request($api_url, $menu);
    return $msg;
  }

  /**
   * 查询菜单
   * http请求方式: GET
   */
  public function queryMenuAPI($token) {
    $file = $_SERVER["DOCUMENT_ROOT"]."/include/json/menu.json";
    $api_url = "https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=".$token;
    $menu = https_request($api_url);
    file_put_contents($file, $menu);
    return $menu;
  }

  /**
   * 删除菜单
   * http请求方式: GET
   */
  public function deleteMenuAPI($token) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$token;
    $msg = https_request($api_url);
    return $msg;
  }
}