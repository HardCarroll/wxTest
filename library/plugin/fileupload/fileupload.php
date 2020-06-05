<?php
header("Content-type:text/html; charset:utf-8;");
date_default_timezone_set("Asia/Shanghai");
if(isset($_POST["token"]) && !empty($_POST["token"])) {
  $token = $_POST["token"];
  switch($token) {
    case "uploadFiles":
      echo proc_uploadFiles($_FILES[$token]);
    break;
  }
}

/**
 * 文件上传处理函数
 * @param $files 待上传的文件
 * @return $state 函数处理结果
 */
function proc_uploadFiles($files) {
  $dir = $_SERVER["DOCUMENT_ROOT"]."/uploads/".date("Ymd/");
  is_dir($dir) or @mkdir($dir, 0777, true);
  $prefix = date("His_");
  
  move_uploaded_file($files["tmp_name"], $dir.$prefix.$files["name"]);
  // multiple files upload process
  // for($i = 0; $i<count($files["size"]); $i++) {
  //   move_uploaded_file($files["tmp_name"][$i], $dir.$prefix.$files["name"][$i]);
  // }

  switch($files["error"]) {
    case 0:
      $state["err_code"] = 0;
      $state["err_msg"] = "上传成功";
      break;
    case 1:
      $state["err_code"] = 1;
      $state["err_msg"] = "上传失败[大小超过upload_max_filesize]";
      break;
    case 2:
      $state["err_code"] = 2;
      $state["err_msg"] = "上传失败[大小超过MAX_FILE_SIZE]";
      break;
    case 4:
      $state["err_code"] = 4;
      $state["err_msg"] = "没有文件被上传";
      break;
    case 6:
      $state["err_code"] = 6;
      $state["err_msg"] = "找不到临时文件";
      break;
  }
  return json_encode($state, 320);
}
