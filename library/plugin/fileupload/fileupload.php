<?php
header("Content-type:text/html; charset:utf-8;");
date_default_timezone_set("Asia/Shanghai");

$files = $_FILES["files"];
$fp = $_SERVER["DOCUMENT_ROOT"]."/uploads/".date("Ymd/");
is_dir($fp) or @mkdir($fp, 0777, true);
$fn = date("His_");

// for($i = 0; $i<count($files["size"]); $i++) {
  move_uploaded_file($files["tmp_name"], $fp.$fn.$files["name"]);
// }
// for($i = 0; $i<count($files["size"]); $i++) {
//   move_uploaded_file($files["tmp_name"][$i], $fp.$fn.$files["name"][$i]);
// }

echo '{"err_code": 0, "err_msg": "ok"}';
