<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/include/include.php");

$appToken = $wxApp->getAppToken();
$randomKey = getRandomKey();

echo "appToken: " . $appToken . "<br/>";
echo "randomKey: " . $randomKey . "<br/>";

// $str = ",hello, world";
// $sep = ",";
// $nPos = strpos($str, $sep);
// if(false === $nPos) {
//   echo "Not Found";
// }
// else {
//   echo $nPos;
// }
// $data = '{"type": "image", "offset": 0, "count": 20}';
// echo $wxMaterial->batchgetMaterialAPI($appToken, $data);
// $data = '{"media_id": "pJOkXK1Z4SWAVjqIfljUhhGy9xTvdE3QI9q8Z5GaWzg"}';
// echo $wxMaterial->getMaterialAPI($appToken, $data);
// $img = ["http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/hg6SBNicDBrFwAYPlSqoqVCQW6xpqkelZByKJ1y2zvVicWNhFtAcxjddiatO6YYVuuZz7ECph1EuUZ3pTaNZI6AVQ\/0?wx_fmt=jpeg", "http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/hg6SBNicDBrFwAYPlSqoqVCQW6xpqkelZByKJ1y2zvVicWNhFtAcxjddiatO6YYVuuZz7ECph1EuUZ3pTaNZI6AVQ\/0?wx_fmt=jpeg", "http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/hg6SBNicDBrFwAYPlSqoqVCQW6xpqkelZByKJ1y2zvVicWNhFtAcxjddiatO6YYVuuZz7ECph1EuUZ3pTaNZI6AVQ\/0?wx_fmt=jpeg", "http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/hg6SBNicDBrFwAYPlSqoqVCQW6xpqkelZByKJ1y2zvVicWNhFtAcxjddiatO6YYVuuZz7ECph1EuUZ3pTaNZI6AVQ\/0?wx_fmt=jpeg", "http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/hg6SBNicDBrFwAYPlSqoqVCQW6xpqkelZByKJ1y2zvVicWNhFtAcxjddiatO6YYVuuZz7ECph1EuUZ3pTaNZI6AVQ\/0?wx_fmt=jpeg", "http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/hg6SBNicDBrFwAYPlSqoqVCQW6xpqkelZByKJ1y2zvVicWNhFtAcxjddiatO6YYVuuZz7ECph1EuUZ3pTaNZI6AVQ\/0?wx_fmt=jpeg"];
// $img[] = '{"media_id": "pJOkXK1Z4SWAVjqIfljUhhGy9xTvdE3QI9q8Z5GaWzg"}';
// $img[] = '{"media_id": "pJOkXK1Z4SWAVjqIfljUhpkUhG7BITTInxfGOzrrUQM"}';
// $img[] = '{"media_id": "pJOkXK1Z4SWAVjqIfljUhp-6ztvHwd6cBqSY_ohM8ig"}';
// $img[] = '{"media_id": "pJOkXK1Z4SWAVjqIfljUhlBBkwFAAhhYPXiI_fmbbd8"}';
// $img[] = '{"media_id": "pJOkXK1Z4SWAVjqIfljUhiwvLnpO27lPY94ZEEVbSkM"}';
// $img[] = '{"media_id": "pJOkXK1Z4SWAVjqIfljUhmr2uOsFNk4TfBrrknuuUeM"}';

// foreach($img as $media) {
//   echo $wxMaterial->deleteMaterialAPI($appToken, $media) . "<br/>";
// }

// echo $wxMaterial->getMaterialCountAPI($appToken);

// print_r($img);
// foreach($img as $str) {
//   $tmp = stripslashes($str);
//   $tmp = str_replace("http://", "", $tmp);
//   $url = str_replace("//", "/", $tmp);
//   echo '<img src="http://'.$url.'" style="width:100px;">';
// }
