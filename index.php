<?php
header("content-type:text/html; charset:utf-8;");
//set timezone
date_default_timezone_set("Asia/Shanghai");

require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/wxCallbackAPI.php");
$appData = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/include/json/config.json"), TRUE);

$wxApp = wxCallbackAPI::getInstance($appData["appId"], $appData["appSecrect"], $appData["Token"]);

// $wxApp->test();

/**
 * 如果有"echostr"字段，说明是一个URL验证请求，
 * 否则是微信用户发过来的信息
 */
if (isset($_GET["echostr"])){
  $wxApp->valid();
}else {
  $wxApp->responseMsg();
}
