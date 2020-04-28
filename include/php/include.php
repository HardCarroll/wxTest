<?php
header("content-type:text/html; charset:utf-8;");
//set timezone
date_default_timezone_set("Asia/Shanghai");

require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/commonAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/wxCallbackAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/modules/wxMenuAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/modules/wxMaterialAPI.php");

$appData = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/include/json/config.json"), TRUE);

$wxApp = wxCallbackAPI::getInstance($appData["appId"], $appData["appSecrect"], $appData["Token"]);
$wxMenu = wxMenuAPI::getInstance();
$wxMaterial = wxMaterialAPI::getInstance();
