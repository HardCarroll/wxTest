<?php
header("content-type:text/html; charset:utf-8;");
//set timezone
date_default_timezone_set("Asia/Shanghai");

require_once($_SERVER["DOCUMENT_ROOT"]."/include/api/commonAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/api/wxCallbackAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/api/wxMenuAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/api/wxMaterialAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/api/wxCustomAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/api/wxUserManageAPI.php");

$wxMenu = wxMenuAPI::getInstance();
$wxMaterial = wxMaterialAPI::getInstance();
$wxCustom = wxCustomAPI::getInstance();
$wxUser = wxUserManageAPI::getInstance();

$handle["wxMenu"] = $wxMenu;
$handle["wxUser"] = $wxUser;
$handle["wxMaterial"] = $wxMaterial;
$handle["wxCustom"] = $wxCustom;

$appData = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/include/json/config.json"), TRUE);

$wxApp = wxCallbackAPI::getInstance($appData["appId"], $appData["appSecrect"], $appData["Token"], $handle);
