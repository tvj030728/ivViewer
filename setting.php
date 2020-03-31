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
	<meta charset="UTF-8">
	<title>설정 페이지 - ivViewer</title>
	<link rel="stylesheet" href="asset/css/bootstrap.min.css">
	<link rel="stylesheet" href="asset/css/all.min.css">
	<link rel="stylesheet" href="asset/css/style.css">
	<link rel="icon" type="image/png" href="asset/favicon.png" />
	<meta name='viewport' content='initial-scale=1, viewport-fit=cover'>
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="manifest" href="manifest.json">
</head>
<body>

	<div class="header flex-column">
		<div class="shape skew position-absolute h-100 w-100">
		</div>
		<div class="header-content position-relative" style="margin-top: 50px;">
			<div class="container">
				<h1 class="site-name display-2 text-white font-weight-bold">설정페이지</h1>
			</div>
		</div>
	</div>
</div>

	<div class="container-fluid content py-4 clearfix">
	<div class="grid">
    <?php if (is_dir("./system/setting/metaload")): ?>
      <a href="setting-up.php?action=data" class="item">
        <div class="card">
          <div class="card-header text-center">
            <div class="item-name">데이터 기반으로 로드</div>
          </div>
        </div>
      </a>
    <?php else: ?>
      <a href="setting-up.php?action=meta" class="item">
        <div class="card">
          <div class="card-header text-center">
            <div class="item-name">메타 기반으로 로드</div>
          </div>
        </div>
      </a>
    <?php endif; ?>

    <?php if (is_dir("./system/setting/noimg")): ?>
      <a href="setting-up.php?action=img" class="item">
        <div class="card">
          <div class="card-header text-center">
            <div class="item-name">이미지로드</div>
          </div>
        </div>
      </a>
    <?php else: ?>
      <a href="setting-up.php?action=noimg" class="item">
        <div class="card">
          <div class="card-header text-center">
            <div class="item-name">이미지 로드하지 않음</div>
          </div>
        </div>
      </a>
    <?php endif; ?>
	</div>

</div>
<footer class="footer w-100" style="padding-top: 0px;margin-top: 50px;">
	<hr class="mt-5 mb-4">
	<div class="text-center my-5 footer-bottom">
		<p>ivViewer - 쉽고 빠른 웹툰 뷰어</p>
	</div>
</footer>

<a href="#" class="back-to-top bg-primary">
	<i class="fas fa-angle-double-up"></i>
</a>

<script src="asset/js/jquery.min.js"></script>
<script src="asset/js/popper.min.js"></script>
<script src="asset/js/bootstrap.min.js"></script>
<script src="asset/js/isotope.pkgd.min.js"></script>
<script src="asset/js/script.js"></script>
</body>
</html>
