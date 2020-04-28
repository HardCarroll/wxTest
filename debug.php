<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/include/php/include.php");

$appToken = $wxApp->getAppToken();
$randomKey = getRandomKey();

echo "appToken: " . $appToken . "<br/>";
echo "randomKey: " . $randomKey . "<br/>";
// echo $wxMenu->queryMenu($appToken) . "<br/>";
// echo $wxMaterial->getMaterialCountAPI($appToken) . "<br/>";
// echo $wxMaterial->batchgetMaterialAPI($appToken, '{"type": "image", "offset": 0, "count": 20}') . "<br/>";
