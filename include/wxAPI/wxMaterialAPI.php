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

  /**
   * 获取永久素材的列表
   * 1、获取永久素材的列表，也包含公众号在公众平台官网素材管理模块中新建的图文消息、语音、视频等素材 
   * 2、临时素材无法通过本接口获取
   * 
   * http请求方式: POST
   * POST数据示例
   * {
   *  "type":TYPE,          // 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
   *  "offset":OFFSET,      // 从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
   *  "count":COUNT         // 返回素材的数量，取值在1到20之间
   * }
   * 
   */
  public function batchgetMaterialAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$token;
    $list = https_request($api_url, $data);
    return $list;
  }

  /**
   * 获取永久素材的总数
   * 1.永久素材的总数，也会计算公众平台官网素材管理中的素材
   * 2.图片和图文消息素材（包括单图文和多图文）的总数上限为5000，其他素材的总数上限为1000
   * 
   * http请求方式: GET
   */
  public function getMaterialCountAPI($token) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=".$token;
    $count = https_request($api_url);
    return $count;
  }

  /**
   * 在新增了永久素材后，开发者可以根据media_id通过本接口下载永久素材。
   * 1.公众号在公众平台官网素材管理模块中新建的永久素材，可通过"获取素材列表"获知素材的media_id。
   * 2.临时素材无法通过本接口获取
   * 
   * http请求方式: POST
   * POST数据示例
   * {
   *  "media_id":MEDIA_ID     // 要获取的素材的media_id
   * }
   * 
   */
  public function getMaterialAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$token;
    $material = https_request($api_url, $data);
    return $material;
  }

  /**
   * 新增永久图文素材
   * 
   * http请求方式: POST
   * POST数据示例
   * {
   *  "articles": [
   *    {
   *      "title": TITLE,                               // 标题
   *      "thumb_media_id": THUMB_MEDIA_ID,             // 图文消息的封面图片素材id（必须是永久mediaID）
   *      "author": AUTHOR,                             // 作者
   *      "digest": DIGEST,                             // 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空。如果本字段为没有填写，则默认抓取正文前64个字。
   *      "show_cover_pic": SHOW_COVER_PIC(0 / 1),      // 是否显示封面，0为不显示，1为显示
   *      "content": CONTENT,                           // 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS。
   *      "content_source_url": CONTENT_SOURCE_URL,     // 图文消息的原文地址，即点击“阅读原文”后的URL
   *      "need_open_comment":1,                        // Uint32 是否打开评论，0不打开，1打开
   *      "only_fans_can_comment":1                     // Uint32 是否粉丝才可评论，0所有人可评论，1粉丝才可评论
   *    },
   *    // 若新增的是多图文素材，则此处应还有几段articles结构
   *  ]
   * }
   * 
   */
  public function addNewsAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=".$token;
    $news = https_request($api_url, $data);
    return $news;
  }

  /**
   * 删除不再需要的永久素材
   * 1.请谨慎操作本接口，因为它可以删除公众号在公众平台官网素材管理模块中新建的图文消息、语音、视频等素材
   * 2.临时素材无法通过本接口删除
   * 
   * http请求方式: POST
   * POST数据示例
   * {
   *  "media_id":MEDIA_ID                              // 要删除的素材的media_id
   * }
   */
  public function deleteMaterialAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }
}