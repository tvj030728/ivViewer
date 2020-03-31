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

if ($_GET['action'] == 'data') {
  rmdir("./setting/metaload");
  header("Location: ".$_SERVER['HTTP_REFERER']);
}
if ($_GET['action'] == 'meta') {
  mkdir("./setting/metaload");
  header("Location: ".$_SERVER['HTTP_REFERER']);
}
if ($_GET['action'] == 'img') {
  rmdir("./setting/noimg");
  header("Location: ".$_SERVER['HTTP_REFERER']);
}
if ($_GET['action'] == 'noimg') {
  mkdir("./setting/noimg");
  header("Location: ".$_SERVER['HTTP_REFERER']);
}
 ?>
