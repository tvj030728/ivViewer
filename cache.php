<?php
$logindata = json_decode(file_get_contents('./config.json'), true);
$logindatauser = $logindata[user];
$logindatapass =$logindata[pass];
if ($_COOKIE['login'] == true) {}else{
	header("Location: ./login/");
}
if (!isset($_COOKIE[$logindatauser])) {
	header("Location: ./login/");
}
if (!isset($_COOKIE[$logindatapass])) {
	header("Location: ./login/");
}
 ?><!DOCTYPE html>
<html lang="ko">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="initial-scale=1, width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
				<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    </head>

    <body>
        <?php
$setting_name = $_GET['cache_name'];
$setting_value = $_GET['cache_value'];
if (isset($_GET['nomsg'])){
	} else {
	$setting_message = $_GET['cache_message'];
	echo "<script>swal({
	  title: '설정 완료!',
	  text: '".$setting_message."',
	  icon: 'success',
	  button: '확인',
	})
	.then((value) => {
	location.replace('".$_SERVER["HTTP_REFERER"]."')
	});
	</script>";
}

setcookie($setting_name, $setting_value, time() + 86400 * 7);

if (isset($_GET['nomsg'])){
	echo "<script>location.replace('".$_SERVER["HTTP_REFERER"]."');</script>";
}

?>

</body>

</html>
