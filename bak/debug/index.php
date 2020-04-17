<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Display Page</title>
</head>
<body>
  <input type="button" value="checkJsApi" id="checkJsApi">
  <input type="button" value="openLocation" id="openLocation">
  <br/>
  <?php
    // require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/wxCommonAPI.php");
    // $wxCommonObj = wxCommonAPI::getInstance("wxfb56a79db24fd301", "ff1493c68e5f0f3aa4d962eb48c0c71b");
    // $signData = $wxCommonObj->getSignData();
  ?>
</body>
</html>

<script type="text/javascript" src="/include/jquery/jquery.min.js"></script>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script type="text/javascript">
  // wx.config({
  //   debug: true, // 是否开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出
  //   appId: '<?php echo $signData->appId; ?>', // 必填，公众号的唯一标识
  //   timestamp: '<?php echo $signData->timestamp; ?>', // 必填，生成签名的时间戳
  //   nonceStr: '<?php echo $signData->nonceStr; ?>', // 必填，生成签名的随机串
  //   signature: '<?php echo $signData->signature; ?>',// 必填，签名
  //   jsApiList: [
  //     'checkJsApi',
  //     'updateAppMessageShareData',
  //     'openLocation',
  //     'closeWindow'
  //   ] // 必填，需要使用的JS接口列表
  // });

  // wx.ready(function() {

  //   document.querySelector("#checkJsApi").onclick = function () {
  //     wx.checkJsApi({
  //       jsApiList: [
  //         'updateTimelineShareData',
  //         'openLocation'
  //       ],
  //       success: function (res) {
  //         alert(JSON.stringify(res));
  //       }
  //     });
  //   };

  //   document.querySelector("#openLocation").onclick = function () {
  //     wx.openLocation({
  //       latitude: 28.245070,
  //       longitude: 113.082940,
  //       name: "花儿朵朵国际艺术体验馆",
  //       address: "长沙市星沙开元中路88号星隆国际1211",
  //       scale: 16,
  //       infoUrl: "http://www.webserv.cn"
  //     });
  //   };

  // });

  // wx.error(function (res) {
  //     alert(res.errMsg);
  //   });


$(function() {
  // $("input[value='getSignData']").off("click").on("click", function() {
  //   $.ajax({
  //     url: "/debug/debug.php",
  //     type: "POST",
  //     data: "token=getSignData",
  //     processData: false,
  //     // contentType: false,   //数据为formData时必须定义此项
  //     // dataType: "json",
  //     success: function(data) {
  //       console.log(data);
  //       // console.log(data.appId);
  //     },
  //     error: function(err) {
  //       console.log("fail: "+err);
  //     }
  //   });
  // });

  $.ajax({
      url: "/debug/debug.php",
      type: "POST",
      data: "token=getSignData",
      processData: false,
      // contentType: false,   //数据为formData时必须定义此项
      dataType: "json",
      success: function(data) {
        // alert(data);
        // console.log(data);
        // console.log(data.appId);

        wx.config({
          debug: true, // 是否开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出
          appId: data.appId, // 必填，公众号的唯一标识
          timestamp: data.timestamp, // 必填，生成签名的时间戳
          nonceStr: data.nonceStr, // 必填，生成签名的随机串
          signature: data.signature,// 必填，签名
          jsApiList: [
          'checkJsApi',
          'updateAppMessageShareData',
          'openLocation',
          'closeWindow'
          ] // 必填，需要使用的JS接口列表
        });

        wx.ready(function() {

          document.querySelector("#checkJsApi").onclick = function () {
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

        });

      },
      error: function(err) {
        console.log("fail: "+err);
      }
    });

});
</script>