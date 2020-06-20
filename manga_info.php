<?php
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

$mAgent = array("iPhone","iPod","Android","Blackberry",
    "Opera Mini", "Windows ce", "Nokia", "sony" );
$chkMobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
    if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
        $chkMobile = true;
        break;
    }
}
 ?><!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title><?php echo $_GET['title']; ?> - ivViewer</title>
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
				<a href="./"><img src="./metadata/titles/<?php echo $_GET['folder']; ?>-<?php echo str_replace("+", "%20", urlencode($_GET['title'])); ?>/thumb.jpg" style="border-radius: <?php if($chkMobile) {echo '1';} else {echo '1';} ?>%;" <?php if($chkMobile) {echo "width='100%'";} ?>></a>
				<br><br>
				<h1 class="site-name display-2 text-white font-weight-bold"><?php echo $_GET['title']; ?></h1>
				<h2 class="header-title text-white"><?php $fp = fopen("./metadata/titles/".$_GET['folder']."-".$_GET['title']."/writer.txt","r"); $fr = fread($fp, filesize("./metadata/titles/".$_GET['folder']."-".$_GET['title']."/writer.txt")); fclose($fp); echo $fr; ?></h2>
				<?php if($chkMobile) {} else {echo "<br>";} ?>
				<h5 class="header-sub-title mt-2">#<?php $fp = fopen("./metadata/titles/".$_GET['folder']."-".$_GET['title']."/genre.txt","r"); $fr = fread($fp, filesize("./metadata/titles/".$_GET['folder']."-".$_GET['title']."/genre.txt")); fclose($fp); echo str_replace(" ", " #", $fr); ?></h5>
				<?php if($chkMobile) {} else {echo "<br>";} ?>
				<h5 class="header-sub-title mt-2"><?php $fp = fopen("./metadata/titles/".$_GET['folder']."-".$_GET['title']."/detail.txt","r"); $fr = fread($fp, filesize("./metadata/titles/".$_GET['folder']."-".$_GET['title']."/detail.txt")); fclose($fp); echo $fr; ?></h5>
				<?php
				if (file_exists("./system/addon/like.php")) {
					include("./system/addon/like.php");
				}
				 ?>
			</div>
		</div>
	</div>
</div>

	<div class="container-fluid content py-4 clearfix">
	<div class="grid">

		<?php
		$dir = "./data/".$_GET['folder']."/".$_GET['title']."/";
		$files = array();
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
						if(strpos($file, "zip") !== false or strpos($file, "cbz") !== false or strpos($file, "png") !== false) {
						    array_push($files, $file);
						}
					}
				}
				closedir($dh);
			}
		}
		natsort($files);
		foreach ($files as $put) {
			$originput = $put;
			$p1 = explode(' ', $put);
			$count = 0;
			$put2 = '';
			foreach ($p1 as $p2) {
				if ($count == 0) {
					$count = $count + 1;
				} else {
					$put2 = $put2 . $p2 . " ";
				}
			}
			$put = str_replace($_GET['title']." ", "", $put2);
			if (file_exists("./system/addon/looked.php")) {
				include("./system/addon/looked.php");
			} else {
			?>
			<a href="./viewer.php?title=<?php echo urlencode($_GET['title']); ?>&episode=<?php echo urlencode($originput); ?>&folder=<?php echo $_GET[folder]; ?>" class="item">
				<div class="card">
					<div class="card-header text-center">
						<div class="item-name" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo str_replace(".zip", "", str_replace(".png", "", str_replace(".cbz", "", $put))); ?></div>
					</div>
				</div>
			</a>
			<?php
			}
		}
		?>
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
