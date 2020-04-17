<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>调试页面</title>
<!-- <link rel="stylesheet" href="../css/weui.css"> -->
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
</head>

<body>
  <?php
  // require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/wxCommonAPI.php");

    /**
   * 根据指定字符池及长度生成随机字符串
   * @param $str_pol：随机字符池，默认为"ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"
   * @param $str_len：要生成的随机字符串长度，默认为16个字符
   * @return $str_key：返回生成的随机字符串
   */
  function getRandomKey($str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz", $str_len = 16) {
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
  function https_request($url, $data = null) {
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

  /**
   * 获取access_token
   * @param string $appid: 公众号id
   * @param string $appsecret: 公众号开发密钥
   * @param bool $flag: 是否强制刷新access_token，默认false，即不强制刷新
   * @return string $token: access_token
   */
  function getToken($appid, $appsecret, $flag = false) {
    // 本地文件路径
    $filepath = $_SERVER["DOCUMENT_ROOT"] . "/include/json/token.json";
    // 获取access_token官方链接
    $api_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    // 获取函数调用时间
    $cur_time = time();

    // 判断是否强制刷新(0)、本地文件是否存在(1)、是否已过期(0)
    if(!$flag and file_exists($filepath) and ($cur_time - json_decode(file_get_contents($filepath), true)["last_time"] < 6000)) {
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
      $tokenArray["last_time"] = $cur_time;
      $tokenArray["time_string"] = date("Y-m-d G:i:s");
    }
    
    // 将access_token数据以JSON格式保存到本地文件中
    file_put_contents($filepath, json_encode($tokenArray));

    return $token;
  }

  /**
   * 获取jsapi_ticket
   * @param string $token: access_token
   * @param bool $flag: 是否强制刷新jsapi_ticket，默认false，即不强制刷新
   * @return string $ticket: jsapi_ticket
   */
  function getTicket($appid, $appsecret) {
    // 获取jsapi_ticket官方链接
    $api_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=" . getToken($appid, $appsecret);
    $ticket = json_decode(https_request($api_url), true)["ticket"];

    return $ticket;
  }
  
  $appId = "wxfb56a79db24fd301";
  $timestamp = time();
  $nonceStr = getRandomKey();
  $ticket = getTicket("wxfb56a79db24fd301", "ff1493c68e5f0f3aa4d962eb48c0c71b");
  $url = "http://test.webserv.cn/debug/index.php";
  $str = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
  $signature = sha1($str);
  // $token = getToken("wxfb56a79db24fd301", "ff1493c68e5f0f3aa4d962eb48c0c71b", false);
  // $ticket = getTicket("wxfb56a79db24fd301", "ff1493c68e5f0f3aa4d962eb48c0c71b");
  // echo $ticket;

  ?>
  
  <input type="button" id="btn" value="CLICK">
  <input type="button" id="openLocation" value="OpenLocation">
  
  <!-- <script type="text/javascript" src="../js/common.js"></script> -->
  <script type="text/javascript">
    
    wx.config({
      debug: false, // 是否开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出
      appId: '<?php //echo $appId; ?>', // 必填，公众号的唯一标识
      timestamp: '<?php //echo $timestamp; ?>', // 必填，生成签名的时间戳
      nonceStr: '<?php //echo $nonceStr; ?>', // 必填，生成签名的随机串
      signature: '<?php //echo $signature; ?>',// 必填，签名
      jsApiList: [
        'checkJsApi',
        'updateAppMessageShareData',
        'openLocation',
        'closeWindow'
      ] // 必填，需要使用的JS接口列表
    });
    
    wx.ready(function () {
      alert("ok");
      document.querySelector("#btn").onclick = function () {
        wx.checkJsApi({
          jsApiList: [
            'updateTimelineShareData',
            'openLocation'
          ],
          success: function (res) {
            alert(JSON.stringify(res));
          }
        });
      };
      
      document.querySelector("#openLocation").onclick = function () {
        wx.openLocation({
          latitude: 28.245070,
          longitude: 113.082940,
          name: "花儿朵朵国际艺术体验馆",
          address: "长沙市星沙开元中路88号星隆国际1211",
          scale: 16,
          infoUrl: "http://www.webserv.cn"
        });
      };
      
      // wx.updateAppMessageShareData({ 
      //   title: 'share title', // 分享标题
      //   desc: 'description', // 分享描述
      //   link: 'http://test.webserv.cn/debug/debug.php', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
      //   imgUrl: 'http://test.webserv.cn/images/cmd.jpg', // 分享图标
      //   }, function(res) { 
      //   //这里是回调函数 
      //   alert(res.errMsg);
      // });

    });
    
    wx.error(function (res) {
      alert(res.errMsg);
    });
    
  </script>
</body>
</html>
