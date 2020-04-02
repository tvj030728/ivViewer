<?php
$logindata = json_decode(file_get_contents('../config.json'), true);
$logindatauser = $logindata[user];
$logindatapass =$logindata[pass];
if ($_COOKIE['login'] == true) {}else{
	die(header("Location: ../login/"));
}
if (!isset($_COOKIE[$logindatauser])) {
	die(header("Location: ../login/"));
}
if (!isset($_COOKIE[$logindatapass])) {
	die(header("Location: ../login/"));
}

if(strpos($_GET['episode'], "zip") !== false) {
  copy("../data/".$_GET['folder']."/".$_GET['title']."/".$_GET['episode'], "../data/temp/".$_GET['title'].$_GET['episode']);
} else {
 if(strpos($_GET['episode'], "png") !== false) {
   copy("../data/".$_GET['folder']."/".$_GET['title']."/".$_GET['episode'], "../data/temp/".$_GET['title'].$_GET['episode']);
 } else {
   die("옳바르지 않은 파일 타입 입니다.");
 }
}
 ?>
