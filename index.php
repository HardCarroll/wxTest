<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/include.php");

/**
 * 如果有"echostr"字段，说明是一个URL验证请求，
 * 否则是微信用户发过来的信息
 */
if (isset($_GET["echostr"])){
  $wxApp->valid();
}else {
  $wxApp->responseMsg();
}

echo "hello world";
