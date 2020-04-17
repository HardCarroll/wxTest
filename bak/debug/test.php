<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width = device-width, initial-scale = 1.0, user-scalable = no">
<title>个人信息</title>
<style>
  html, body {
    margin: 0;
    padding: 0;
  }
  .information {
    padding: 20px 10px;
  }
  .photo {
    width: 132px;
    height: 132px;
    margin: 0 auto;
    border-radius: 50%;
    box-sizing: border-box;
    border: 1px solid #ccc;
    overflow: hidden;
    margin-bottom: 20px;
  }
  .photo img {
    width: 100%;
    vertical-align: top;
    text-align: center;
    line-height: 132px;
  }
  .detail {
    width: 75%;
    margin-left: 25%;
  }
  .weui-footer__text {
    text-align: center;
  }
</style>
</head>
<body>
  <?php
  require_once($_SERVER["DOCUMENT_ROOT"]."/lib/lib.php");

  $dbo = new DBOperator("localhost", "admin", "admin", "wx_test");
  $sql = "SELECT * FROM tab_oauth WHERE openid = 'oQg0C1jo5JwojGLoOSo64JFZGQ3w'";
  $ret = $dbo->execute($sql);
  print_r(mysqli_fetch_array($ret,MYSQLI_ASSOC));


  $filepath = $_SERVER["DOCUMENT_ROOT"] . "/json/oauth2.json";
  $appid = APPID;
  $appsecret = APPSECRET;
  $code = $_GET["code"];
  $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";

  $retToken = Common::https_request($url);
  $result = json_decode($retToken, true);
  $infoURL = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$result["access_token"].'&openid='.$result["openid"].'&lang=zh_CN';
  $retInfo = Common::https_request($infoURL);
  $infoArray = json_decode($retInfo, true);
  $headimgurl = $infoArray["headimgurl"];
  
  $sex_cn = array("未知", "男", "女");
  //
//  echo $infoArray;

  //$url = "http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJNnw8xZhqfEic1AQ3ppDfia198XBC5UHFEBufvBceIbm8nARbnEDiahicapIcFYp8bRVF6OO1hmf67SQ\/132";
  //$retURL = str_replace("\\", "", $url);
  //
  //echo $retURL;

  //跳转到指定URL
  //header("Location: $retURL");
  ?>
  <section class="information">
    <div class="photo">
      <img src="<?php echo str_replace("\\", "", $headimgurl) ?>" alt="photo">
    </div>
    <div class="detail nickname">
      <span>昵称：</span><span><?php echo $infoArray["nickname"]; ?></span>
    </div>
    <div class="detail sex">
      <span>性别：</span><span><?php echo $sex_cn[$infoArray["sex"]]; ?></span>
    </div>
    <div class="detail location">
      <span>地区：</span><span><?php echo $infoArray["country"] . " " . $infoArray["province"] . " " . $infoArray["city"] . " "; ?></span>
    </div>
  </section>
  
  <p><?php echo $_SERVER['SERVER_PORT']; ?></p>
  
  <div style="display: block; position: absolute; bottom: 0; left: 0;">
    <p style="text-align: center;">Copyright © 2018-2019 黄狮虎</p>
  </div>
</body>
</html>