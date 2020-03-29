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

$account = array('user'=>sha1($_POST[user]), 'pass'=>sha1($_POST[pass]));
$myfile = fopen("../config.json", "w") or die("오류발생!");
fwrite($myfile, json_encode($account, JSON_UNESCAPED_UNICODE));
fclose($myfile);
header("Location: ../?response=account&id=".$_POST[user]."&pw=".$_POST[pass])
 ?>
