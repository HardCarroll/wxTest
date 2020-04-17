<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/wxCommonAPI.php");
// echo __FILE__;
// echo "<br/>";
// echo getTicket("wxfb56a79db24fd301", "ff1493c68e5f0f3aa4d962eb48c0c71b");
// print_r($ticket);

// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// echo $url;

$wxCommonObj = wxCommonAPI::getInstance("wxfb56a79db24fd301", "ff1493c68e5f0f3aa4d962eb48c0c71b");
// echo $wxCommonObj->getToken();
// echo "<br/>";
// echo $wxCommonObj->getTicket();
// echo "<br/>";
// print_r($wxCommonObj->getSignData());
echo json_encode($wxCommonObj->getSignData());
// echo $wxCommonObj->getURL();

// function getSignData() {
//   $appId = "wxfb56a79db24fd301";
//   $timestamp = time();
//   $nonceStr = getRandomKey();
//   $ticket = getTicket("wxfb56a79db24fd301", "ff1493c68e5f0f3aa4d962eb48c0c71b");
//   $url = "http://test.webserv.cn/debug/index.php";
//   $str = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
//   $signature = sha1($str);
//   return $signData;
// }