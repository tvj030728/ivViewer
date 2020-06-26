<?php
error_reporting(0);
$logindata = json_decode(file_get_contents('./config.json'), true);
$logindatauser = $logindata[user];
$logindatapass =$logindata[pass];
if ($_COOKIE['login'] == true) {}else{
	die(header("Location: ./login/"));
}
if (!isset($_COOKIE[$logindatauser])) {
	die(header("Location: ./login/"));
}
if (!isset($_COOKIE[$logindatapass])) {
	die(header("Location: ./login/"));
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
				<h1 class="site-name display-2 text-white font-weight-bold" style="cursor:pointer;" onclick="location.href='./';">ivViewer</h1>
				<h2 class="header-title text-white" id="msgivvewer"></h2>
				<?php
				if (file_exists("./system/addon/lookedrecent.php")) {
					include("./system/addon/lookedrecent.php");
				}
				 ?>
			</div>
		</div>
	</div>

	<div class="container-fluid content py-4 clearfix">
		<?php
		if (file_exists("./system/addon/searchform.php")) {
			include("./system/addon/searchform.php");
		}
		 ?>
		<nav class="navbar navbar-light navbar-expand bg-light mb-4 p-2 rounded">
			<ul class="navbar-nav">
				<li class="nav-item ">
					<a href="#" class="nav-link active" data-filter="*">전체보기</a>
				</li>
				<?php
				if (is_dir("./system/addon/dump/like/")) {
					?>
					<li class="nav-item ">
						<a href="#" class="nav-link" data-filter=".즐겨찾기">즐겨찾기</a>
					</li>
					<?php
				}
				 ?>
				<?php
				$files = array();
				$dir = "./metadata/titles/";
				if (is_dir($dir)){
					if ($dh = opendir($dir)){
						while (($file = readdir($dh)) !== false){
							if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir" || $file == "temp") { continue; } else {
								$mft = explode('-',$file);
								$count = 0;
								$count1 = 0;
								foreach ($mft as $mft1) {
									if ($count == 0) {
										$mftfolder = $mft1;
										$count = 1;
									} else {
										if ($count1 == 0) {
											$mftname = $mft1;
											$count1 = 1;
										} else {
											$mftname = $mftname . "-".$mft1;
										}
									}
								}
								array_push($files, array($mftname, $mftfolder));
								$mftfolderload[] = $mftfolder;
							}
						}
						closedir($dh);
					}
				}
				sort($files);
				foreach(array_unique($mftfolderload) as $mftfolder){
				?>
				<li class="nav-item">
					<a href="#" class="nav-link" data-filter=".<?php echo $mftfolder; ?>">[<?php echo $mftfolder; ?>]</a>
				</li>
				<?php
			}

				$dir = "./metadata/genre/";

				if (is_dir($dir)){
				  if ($dh = opendir($dir)){
				    while (($file = readdir($dh)) !== false){
							if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
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

		foreach ($files as $file) {
			if (file_exists("./system/addon/search.php")) {
				include("./system/addon/search.php");
			} else {
				if (!file_exists("./metadata/titles/$file[1]-$file[0]/titleid.txt")) {
			    ?>
			    <a title="<?php echo $file[1]; ?>/<?php echo $file[0]; ?>" href="./manga_info.php?title=<?php echo $file[0]; ?>&folder=<?php echo $file[1]; ?>" class="<?php echo $file[1]; ?> <?php if(is_file("./system/addon/dump/like/".$file[1]."---".$file[0].".like")) { echo "즐겨찾기"; } ?> item">
			      <div class="card">
			        <div class="card-header text-center">
			          <div class="item-name"><?php echo $file[0]; ?></div>
			          <div class="item-category">메타데이터가 등록되지 않음</div>
			        </div>
			        <?php if (!is_dir("./system/setting/noimg")): ?>
			          <img class="card-img-bottom lazy" data-original="./system/dthumb.png" />
			        <?php endif; ?>
			      </div>
			    </a>
			    <?php
			  } else {
			    ?>
			    <a title="<?php echo $file[1]; ?>/<?php echo $file[0]; ?>" href="./manga_info.php?title=<?php echo $file[0]; ?>&folder=<?php echo $file[1]; ?>#id=<?php $fp = fopen("./metadata/titles/$file[1]-$file[0]/titleid.txt","r"); $fr = fread($fp, filesize("./metadata/titles/$file[1]-$file[0]/titleid.txt")); fclose($fp); echo $fr; ?>" class="<?php $fp = fopen("./metadata/titles/$file[1]-$file[0]/genre.txt","r"); $fr = fread($fp, filesize("./metadata/titles/$file[1]-$file[0]/genre.txt")); fclose($fp); echo $fr; ?> <?php echo $file[1]; ?> <?php if(is_file("./system/addon/dump/like/".$file[1]."---".$file[0].".like")) { echo "즐겨찾기"; } ?> item">
			      <div class="card">
			        <div class="card-header text-center">
			          <div class="item-name"><?php echo $file[0]; ?></div>
			          <div class="item-category"><?php $fp = fopen("./metadata/titles/$file[1]-$file[0]/writer.txt","r"); $fr = fread($fp, filesize("./metadata/titles/$file[1]-$file[0]/writer.txt")); fclose($fp); echo $fr; ?></div>
			        </div>
			        <?php if (!is_dir("./system/setting/noimg")): ?>
			          <img class="card-img-bottom lazy" data-original="./metadata/titles/<?php echo str_replace("+", "%20", urlencode($file[1].'-'.$file[0])); ?>/thumb.jpg">
			        <?php endif; ?>
			      </div>
			    </a>
			    <?php
			  }
			}
		}
		?>

	</div>

</div>
<footer class="footer w-100">
	<hr class="mt-5 mb-4">
	<div class="text-center my-5 footer-bottom">
		<p><span style="cursor:pointer;" onclick="location.href='./system/metadata.php?action=start';">메타데이터 등록</span>  |  <span style="cursor:pointer;" onclick="location.href='./login/change.php';">계정정보 변경</span>  |  <span style="cursor:pointer;" onclick="location.href='./setting.php';">설정</span>  |  <span style="cursor:pointer;" onclick="location.replace('login_ok.php?action=logout');">로그아웃</span></p>

		<p><a href="https://ivlis.kr">ivViewer</a>. Developed by <a href="https://ivlis.kr">ivLis.kr</a></p>
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
<?php
$domain = $_SERVER["HTTP_HOST"].str_replace("index.php", "", $_SERVER['PHP_SELF']);
$fp = fopen("./version.php","r"); $currentversion = fread($fp, filesize("./version.php")); fclose($fp);
$currentversion = str_replace("\n", "", $currentversion);
$valcheckurl = "https://static.ivlis.kr/ivViewer/update_check.php?version=$currentversion&domain=$domain";
 ?>
<script>
$("img.lazy").lazyload({
 effect : "fadeIn",
 threshold : 200000
});

$(document).ready(function () {
$("#msgivvewer").load("<?php echo $valcheckurl; ?>");
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
