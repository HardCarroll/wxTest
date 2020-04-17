<?php
$redirect_url = urlencode("http://test.webserv.cn/oauth2/display.php");
//$state_url = urlencode("http://test.webserv.cn/oauth2/user/get_info.php");
$state = sha1("token");
$appid = "wxfb56a79db24fd301";
$api_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";
header("location: $api_url");
exit;
