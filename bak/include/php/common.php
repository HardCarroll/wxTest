<?php
header("content-type:text/html; charset:utf-8;");
//set timezone
date_default_timezone_set("Asia/Shanghai");

/**
 * 根据指定字符池及长度生成随机字符串
 * @param $str_pol：随机字符池，默认为"ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"
 * @param $str_len：要生成的随机字符串长度，默认为16个字符
 * @return $str_key：返回生成的随机字符串
 */
function getRandomKey($str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz", $str_len = 16) {
  $str_key = "";
  for($i = 0; $i < $str_len; $i++)
  {
    $str_key .= $str_pol[mt_rand(0, strlen($str_pol)-1)];//生成php随机数
  }
  return $str_key;
}

/**
 * 发送请求
 * @param $url：地址
 * @param $data：发送的数据
 * @return $output：请求返回的结果
 */
function https_request($url, $data = null) {
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  if (!empty($data)){
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  }
  $output = curl_exec($curl);
  curl_close($curl);
  return $output;
}