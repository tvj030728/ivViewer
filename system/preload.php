<?php
$logindata = json_decode(file_get_contents('../config.json'), true);
$logindatauser = $logindata[user];
$logindatapass =$logindata[pass];
if ($_COOKIE['login'] == true) {}else{
	header("Location: ../login/");
}
if (!isset($_COOKIE[$logindatauser])) {
	header("Location: ../login/");
}
if (!isset($_COOKIE[$logindatapass])) {
	header("Location: ../login/");
}

$basefolder = "../data/naver/";

if(strpos($_GET['episode'], "zip") !== false) {
  copy($basefolder."/".$_GET['title']."/".$_GET['episode'], "../data/temp/".$_GET['title'].$_GET['episode']);
} else {
 if(strpos($_GET['episode'], "png") !== false) {
   copy($basefolder."/".$_GET['title']."/".$_GET['episode'], "../data/temp/".$_GET['title'].$_GET['episode']);
 } else {
   die("옳바르지 않은 파일 타입 입니다.");
 }
}
 ?>
