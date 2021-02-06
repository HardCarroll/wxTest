<?php
header("content-type:text/html; charset:utf-8;");
//set timezone
date_default_timezone_set("Asia/Shanghai");

require_once($_SERVER["DOCUMENT_ROOT"]."/include/wxAPI/commonAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/wxAPI/wxCallbackAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/wxAPI/wxMenuAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/wxAPI/wxMaterialAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/wxAPI/wxCustomAPI.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/wxAPI/wxUserManageAPI.php");

$wxMenu = wxMenuAPI::getInstance();
$wxMaterial = wxMaterialAPI::getInstance();
$wxCustom = wxCustomAPI::getInstance();
$wxUser = wxUserManageAPI::getInstance();

$handle["wxMenu"] = $wxMenu;
$handle["wxUser"] = $wxUser;
$handle["wxMaterial"] = $wxMaterial;
$handle["wxCustom"] = $wxCustom;

$appData = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/include/json/config.json"), TRUE);
/** 
 * @param $appData: 用于获取鉴权
 * 格式: array {
 *  "appId" => "公众号id",
 *  "appSecrect" => "公众号secrect",
 *  "Token" => "由开发者任意填写，用作生成签名"
 * }
 * 
 */
$wxApp = wxCallbackAPI::getInstance($appData, $handle);
// $wxApp = wxCallbackAPI::getInstance($appData["appId"], $appData["appSecrect"], $appData["Token"], $handle);
