<?php
if ($_COOKIE['login'] == true) {}else{
	header("Location: ./login/");
}
?><!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>ivViewer</title>
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
				<h1 class="site-name display-2 text-white font-weight-bold">ivViewer</h1>
				<h2 class="header-title text-white">쉽고 빠르게 다운로드한 웹툰을 감상하세요.</h2>
			</div>
		</div>
	</div>

	<div class="container-fluid content py-4 clearfix">
		<nav class="navbar navbar-light navbar-expand bg-light mb-4 p-2 rounded">
			<ul class="navbar-nav">
				<li class="nav-item ">
					<a href="#" class="nav-link active" data-filter="*">전체보기</a>
				</li>
				<?php
				$dir = "./metadata/genre/";

				if (is_dir($dir)){
				  if ($dh = opendir($dir)){
				    while (($file = readdir($dh)) !== false){
							if($file == "." || $file == "..") { continue; } else {
					?>
					<li class="nav-item">
						<a href="#" class="nav-link" data-filter=".<?php echo $file; ?>"><?php echo $file; ?></a>
					</li>
					<?php
							}
				    }
				    closedir($dh);
				  }
				}
				?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						더보기
					</a>
					<div class="dropdown-menu category-list" aria-labelledby="navbarDropdown">

					</div>
				</li>
			</ul>
	</nav>

	<div class="grid">

		<?php
		$dir = "./metadata/titles/";
		$files = array();
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					if($file == "." || $file == "..") { continue; } else {
						array_push($files, $file);
					}
				}
				closedir($dh);
			}
		}
		sort($files);
		foreach ($files as $file) {
			?>
			<a href="./manga_info.php?title=<?php echo $file; ?>" class="<?php $fp = fopen("./metadata/titles/$file/genre.txt","r"); $fr = fread($fp, filesize("./metadata/titles/$file/genre.txt")); fclose($fp); echo $fr; ?> item">
				<div class="card">
					<div class="card-header text-center">
						<div class="item-name"><?php echo $file; ?></div>
						<div class="item-category"><?php $fp = fopen("./metadata/titles/$file/writer.txt","r"); $fr = fread($fp, filesize("./metadata/titles/$file/writer.txt")); fclose($fp); echo $fr; ?></div>
					</div>
					<img class="card-img-bottom lazy" data-original="./metadata/titles/<?php echo str_replace("+", "%20", urlencode($file)); ?>/thumb.jpg">
				</div>
			</a>
			<?php
		}
		?>

	</div>

</div>
<footer class="footer w-100">
	<hr class="mt-5 mb-4">
	<div class="text-center my-5 footer-bottom">
		<p><span style="cursor:pointer;" onclick="location.replace('./system/metadata-creator.php')">메타데이터 등록</span>  |  <span style="cursor:pointer;" onclick="location.replace('./login/change.php')">계정정보 변경</span>  |  <span style="cursor:pointer;" onclick="location.replace('login_ok.php?action=logout')">로그아웃</span></p>

		<p><a href="https://ivlis.kr">ivViewer</a> - 쉽고 빠른 웹툰 뷰어</p>
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
<script src="asset/js/jquery.lazyload.min.js"></script>
<script>
$("img.lazy").lazyload({
 effect : "fadeIn",
 threshold : 20000
});
</script>
<?php if ($_GET['response'] == "metafin"): ?>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
	swal({
		title: '완료!',
		text: '메타데이터 등록이 완료되었습니다!',
		icon: 'success',
		button: '확인',
	});
	history.pushState('', '', './');
	</script>
<?php endif; ?>
<?php if ($_GET['response'] == "account"): ?>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
	swal({
		title: '완료!',
		text: '아이디:<?php echo $_GET[id]; ?>\n비밀번호:<?php echo $_GET[pw]; ?>\n정보가 변경되었습니다!',
		icon: 'success',
		button: '확인',
	});
	history.pushState('', '', './');
	</script>
<?php endif; ?>
</body>
</html>
