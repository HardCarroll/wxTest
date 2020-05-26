<?php
class wxUserManageAPI {
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
   * 创建标签
   * 一个公众号，最多可以创建100个标签。
   * 
   * http请求方式：POST
   * POST数据格式
   * {
   *  "tag": {
   *    "name": TagName     //标签名
   *   }
   * }
   */
  public function createTagsAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }

  /**
   * 获取公众号已创建的标签
   * http请求方式：GET
   */
  public function getTagsAPI($token) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$token;
    $tags = https_request($api_url);
    return $tags;
  }

  /**
   * 编辑标签
   * http请求方式：POST
   * {
   *  "tag": {
   *    "id": TagId
   *    "name": TagName     //标签名
   *   }
   * }
   */
  public function updateTagsAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/update?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }

  /**
   * 删除标签
   * 当某个标签下的粉丝超过10w时，不可直接删除标签。
   * 先进行取消标签的操作，才可直接删除该标签。
   * 
   * http请求方式：POST
   * POST数据格式
   * {
   *  "tag": {
   *    "id": TagId
   *  }
   * }
   */
  public function deleteTagsAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }

  /**
   * 获取标签下粉丝列表
   * 
   * http请求方式：POST
   * POST数据格式：
   * {
   *  "tagid": TagId,
   *  "next_openid":""                //第一个拉取的OPENID，不填默认从头开始拉取
   * }
   * 
   */
  public function getUserOfTagsAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=".$token;
    $list = https_request($api_url, $data);
    return $list;
  }

  /**
   * 批量为用户打标签
   * 
   * http请求方式：POST
   * POST数据格式：
   * {
   *  "openid_list": [
   *    "ocYxcuAEy30bX0NXmGn4ypqx3tI0",         // 用户1openid
   *    "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"          // 用户2openid
   *  ],
   *  "tagid": TagId                            // 标签id
   * }
   */
  public function taggingUserAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }

  /**
   * 批量为用户取消标签
   * 
   * http请求方式：POST
   * POST数据格式：
   * {
   *  "openid_list": [
   *    "ocYxcuAEy30bX0NXmGn4ypqx3tI0",         // 用户1openid
   *    "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"          // 用户2openid
   *  ],
   *  "tagid": TagId                            // 标签id
   * }
   */
  public function untaggingUserAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }

  /**
   * 获取用户身上的标签列表
   * 
   * http请求方式：POST
   * POST数据格式：
   * {
   *  "openid": "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"    // 用户openid
   * } 
   */
  public function getUserTagsAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }

  /**
   * 通过OpenID来获取用户基本信息
   * http请求方式: GET
   * @param $token    调用接口凭证
   * @param $openid   用户标识
   */
  public function getUserInfoAPI($token, $openid, $lang = "zh_CN") {
    $api_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid."&lang=".$lang;
    $info = https_request($api_url);
    return $info;
  }

  /**
   * 批量获取用户基本信息，最多支持一次拉取100条。
   * http请求方式: POST
   * POST数据格式：
   * {"user_list": [
   *    {
   *      "openid": "otvxTs4dckWG7imySrJd6jSi0CWE",
   *      "lang": "zh_CN"
   *    },
   *    {
   *      "openid": "otvxTs4dckWG7imySrJd6jSi0CWE",
   *      "lang": "zh_CN"
   *    }
   *  ]
   * }
   */
  public function batchgetUserInfoAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=".$token;
    $info = https_request($api_url, $data);
    return $info;
  }

  /**
   * 通过该接口对指定用户设置备注名，该接口暂时开放给微信认证的服务号。
   * 
   * http请求方式: POST
   * POST数据格式：
   * {
   *  "openid":"oDF3iY9ffA-hqb2vVvbr7qxf6A0Q",              // 用户标识
   *  "remark":"pangzi"                                     // 新的备注名，长度必须小于30字符
   * }
   * 
   */
  public function remarkUserAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=".$token;
    $result = https_request($api_url, $data);
    return $result;
  }

  /**
   * 通过本接口来获取帐号的关注者列表。一次拉取调用最多拉取10000个关注者的OpenID，可以通过多次拉取的方式来满足需求。
   * 
   * http请求方式: GET
   * @param $token    调用接口凭证
   * @param $nextid   第一个拉取的OPENID，不填默认从头开始拉取
   * 
   */
  public function getUserListAPI($token, $nextid = "") {
    $api_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$token."&next_openid=".$nextid;
    $list = https_request($api_url);
    return $list;
  }

  /**
   * 获取公众号的黑名单列表
   * http请求方式：POST
   * POST数据格式：
   * {
   *  "begin_openid": OPENID
   * }
   * 当 begin_openid 为空时，默认从开头拉取。
   */
  public function getBlacklistAPI($token, $data = '{"begin_openid": ""}') {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=".$token;
    $list = https_request($api_url, $data);
    return $list;
  }

  /**
   * 批量拉黑用户，一次拉黑最多允许20个
   * http请求方式：POST
   * POST数据格式：
   * {
   *  "openid_list": ["OPENID1", "OPENID2"]
   * }
   */
  public function batchBlacklistAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=".$token;
    $list = https_request($api_url, $data);
    return $list;
  }

  /**
   * 取消拉黑用户，一次取消拉黑最多允许20个
   * http请求方式：POST
   * POST数据格式：
   * {
   *  "openid_list": ["OPENID1", "OPENID2"]
   * }
   */
  public function batchUnblacklistAPI($token, $data) {
    $api_url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=".$token;
    $list = https_request($api_url, $data);
    return $list;
  }
}