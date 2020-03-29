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
 ?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<title>회원정보 변경</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>


	<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
		<div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
			<form class="login100-form validate-form" action="./edit.php" method="POST">
				<span class="login100-form-title p-b-37">
					ivViewer
				</span>

				<div class="wrap-input100 validate-input m-b-20" data-validate="아이디">
					<input class="input100" type="text" name="user" placeholder="아이디">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "비밀번호">
					<input class="input100" type="password" name="pass" placeholder="비밀번호">
					<span class="focus-input100"></span>
				</div>

				<div class="container-login100-form-btn">
					<button class="login100-form-btn">
						변경
					</button>
				</div>

			</form>


		</div>
	</div>



	<div id="dropDownSelect1"></div>

	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<script src="js/main.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php if ($_GET['response'] == "wrong"): ?>
	<script>swal({
		title: '오류!',
		text: '옳바르지 않은 계정입니다.',
		icon: 'error',
		button: '확인',
	});
	history.pushState('', '', './');
	</script>
<?php endif; ?>
</body>
</html>
