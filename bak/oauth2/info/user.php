<?php
// 判断是否在微信内置浏览器打开的
$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
  header("location: ../get_code.php");
  exit;
}
else {
  if (isset($_COOKIE[session_name()])) {
    session_id($_COOKIE[session_name()]);
  }
  session_start();

  if (isset($_SESSION["data_token"])) {
    $data_token = $_SESSION["data_token"];
  }
  else {
    header("location: ../get_code.php");
  }

  if (isset($_SESSION["data_info"])) {
    $data_info = $_SESSION["data_info"];
  }
  else {
    // 通过access_token和openid获得对应用户的相关信息
    $infoURL = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$data_token["access_token"].'&openid='.$data_token["openid"].'&lang=zh_CN';
    $infoArray = json_decode(file_get_contents($infoURL), true);

    // 判断access_token是否过期，如果过期则刷新一下并重新拉取用户信息
    if (isset($infoArray["errcode"])) {
      $refresh_token = $data_token["refresh_token"];
      $refresh_url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$appid&grant_type=refresh_token&refresh_token=$refresh_token";
      $refreshArray = json_decode(file_get_contents($refresh_url), true);

      // 更新session字段数据
      $_SESSION["data_token"] = $refreshArray;

      // 重新拉取用户信息并转换成数组形式
      $infoArray = json_decode(file_get_contents($infoURL), true);
    }

    $data_info = $_SESSION["data_info"] = $infoArray;
  }
  $sex_cn = array("未知", "男", "女");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>个人资料</title>
<link rel="stylesheet" href="../../css/weui.min.css">
<link rel="stylesheet" href="../../css/user.css">
</head>

<body>
  <div class="page">
    <section class="header">
    </section>
    <section class="content">
      <div class="nick"><span>昵称：</span><span><?php echo $data_info["nickname"]; ?></span></div>
      <div class="tel"><span>手机：</span><span>test</span></div>
      <div class="sex"><span>性别：</span><span><?php echo $sex_cn[$data_info["sex"]] ?></span></div>
      <div class="card"><span>卡号：</span><span>test</span></div>
      <div class="card"><span>地区：</span><span><?php echo $data_info["country"]." ".$data_info["province"]." ".$data_info["city"]; ?></span></div>
    </section>
  </div>
  <section class="footer">
    <div class="weui-footer">
      <p class="weui-footer__text">Copyright © 2018-2020 黄狮虎@攻城狮</p>
    </div>
  </section>
</body>
</html>