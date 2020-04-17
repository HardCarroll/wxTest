<?php
echo "debug file";
echo "<br>";
$fp = $_SERVER['DOCUMENT_ROOT'] . "/log/";
$fn = "debug";
$ext = ".txt";
if(is_dir($fp) or @mkdir($path, 0777, true)) {
  echo file_put_contents($fp.$fn.$ext, "Hello world");
}
echo "<br>";
echo file_get_contents($fp.$fn.$ext);

$dirArray = scandir($_SERVER["DOCUMENT_ROOT"]);
foreach($dirArray as $dirName) {
  if(is_file($dirName)) {
    echo $dirName . "<br/>";
  }
}
echo "<br/>";
var_dump($dirArray);

echo "<br/>";

// echo file_get_contents($_SERVER["DOCUMENT_ROOT"]."/json/menu.json");
// echo file_put_contents($_SERVER["DOCUMENT_ROOT"]."/include/json/test.txt", "test file\n");