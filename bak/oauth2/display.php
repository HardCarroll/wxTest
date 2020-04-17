<?php
// 判断是否在微信内置浏览器打开的
$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
  header("location: get_code.php");
  exit;
}
else {
  if (isset($_GET["code"]) && sha1("token") == $_GET["state"]) {

    $code = $_GET["code"];
    $state = $_GET["state"];
    
    if (isset($_COOKIE[session_name()])) {
      session_id($_COOKIE[session_name()]);
    }
    session_start();
    
    if (isset($_SESSION["data_token"])) {
      $data_token = $_SESSION["data_token"];
    }
    else {
      require($_SERVER["DOCUMENT_ROOT"]."/lib/lib.php");
    
      $appid = APPID;
      $appsecret = APPSECRET;

      // 通过code换取access_token和openid
      $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
      $tokenArray = json_decode(file_get_contents($url), true);
      if (isset($tokenArray["errcode"])) {
        header("location: get_code.php");
        exit;
      }
      
      $data_token = $_SESSION["data_token"] = $tokenArray;
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
  else {
    header("location: get_code.php");
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>会员中心</title>
<link rel="stylesheet" href="../css/weui.min.css">
<link rel="stylesheet" href="../css/display.css">
</head>
<body>
  <div class="page">
    <section class="card">
      <div class="photo">
        <img src="<?php echo $data_info['headimgurl'] ?>" alt="head_image">
      </div>
      <div class="nickname">
        <p><?php echo $data_info["nickname"]; ?></p>
      </div>
    </section>
    <section class="weui-flex pt10 pb10">
      <div class="weui-flex__item balance">
        <a href="javascript:;" id="btn_update_balance">
          <section class="counts" id="cnt_balance">0.00</section>
          <section class="cls_balance">余额(元)</section>
        </a>
      </div>
      <div class="weui-flex__item bonus">
        <a href="javascript:;" id="btn_update_bonus">
          <section class="counts" id="cnt_bonus">0</section>
          <section class="cls_bonus">积分(分)</section>
        </a>
      </div>
    </section>
    <section class="weui-cells control">
      <a href="./info/user.php" class="weui-cell weui-cell_access" id="user_detail">
        <div class="weui-cell__hd">
          <img src="../images/cmd.jpg" alt="icon" style="width:20px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
          <p>个人信息</p>
        </div>
        <div class="weui-cell__ft"></div>
      </a>
      <a href="javascript:;" class="weui-cell weui-cell_access">
        <div class="weui-cell__hd">
          <img src="../images/cmd.jpg" alt="icon" style="width:20px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
          <p>积分记录</p>
        </div>
        <div class="weui-cell__ft"></div>
      </a>
      <a href="javascript:;" class="weui-cell weui-cell_access">
        <div class="weui-cell__hd">
          <img src="../images/cmd.jpg" alt="icon" style="width:20px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
          <p>电子礼券</p>
        </div>
        <div class="weui-cell__ft"></div>
      </a>
    </section>
    <section id="toast_update" style="display: none;">
      <div class="weui-mask_transparent"></div>
      <div class="weui-toast toast-self">
        <p class="weui-toast__content">已更新</p>
      </div>
    </section> 
  </div>
  <section class="footer">
    <div class="weui-footer">
      <p class="weui-footer__text">Copyright © 2018-2020 黄狮虎@攻城狮</p>
    </div>
  </section>
</body>
</html>
<script type="text/javascript" src="../js/zepto.min.js"></script>
<script type="text/javascript">
  // toast
  $(function(){
    var $toast = $('#toast_update');
    $('#btn_update_balance').on('click', function(){
      $('#cnt_balance').text(<?php echo $balance; ?>);
      if ($toast.css('display') != 'none') return;
      $toast.fadeIn(100);
      setTimeout(function () {
        $toast.fadeOut(100);
      }, 1000);
    });
    $('#btn_update_bonus').on('click', function(){
      $('#cnt_bonus').text(<?php echo $bonus; ?>);
      if ($toast.css('display') != 'none') return;
      $toast.fadeIn(100);
      setTimeout(function () {
        $toast.fadeOut(100);
      }, 1000);
    });
  });
</script>
