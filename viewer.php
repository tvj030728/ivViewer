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


if (!is_dir('./data/temp/')) {
	mkdir('./data/temp/');
}

$basefolder = "data/".$_GET['folder']."/";

$randcode = rand(1,20);

if(strpos($_GET['episode'], "zip") !== false or strpos($_GET['episode'], "cbz") !== false) {
	$type = "zip";
} else {
	if(strpos($_GET['episode'], "png") !== false) {
		$type = "png";
	} else {
	  die("옳바르지 않은 파일 타입 입니다.");
	}
}

if($type == "zip" or $type == "cbz"){
	if (file_exists("data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode']))) {
	} else {
		copy($basefolder."/".$_GET['title']."/".$_GET['episode'], "data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode']));
	}

	$za = new ZipArchive();

	$za->open("data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode']));

	$list = array();

	for( $i = 0; $i < $za->numFiles; $i++ ){
	    array_push($list, $za->statIndex( $i )[name]);
	}
	natsort($list);

	function imgsrc($file){
	  $load = "zip://data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode'])."#".$file;
	  $data = file_get_contents($load);
	  echo "<img alt='$file' src='data:".mime_content_type($load).";base64,".base64_encode($data)."' />";
	}

	//realname

	$p1 = explode(' ', $_GET[episode]);
	$count = 0;
	foreach ($p1 as $p2) {
		if ($count == 0) {
			$count = $count + 1;
		} else {
			$put2 = $put2 . $p2 . " ";
		}
	}
	$put2 = str_replace($_GET['title']." ", "", $put2);
	$episode = str_replace(".zip", "", str_replace(".cbz", "", $put2));
} elseif ($type == "png") {
	if (file_exists("data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode']))) {
	} else {
		copy($basefolder."/".$_GET['title']."/".$_GET['episode'], "data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode']));
	}
	function imgsrc(){
		$data = file_get_contents("data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode']));
		echo "<img src='data:image/jpeg;base64,".base64_encode($data)."' />";
	}
	$p1 = explode(' ', $_GET[episode]);
	$count = 0;
	foreach ($p1 as $p2) {
		if ($count == 0) {
			$count = $count + 1;
		} else {
			$put2 = $put2 . $p2 . " ";
		}
	}
	$put2 = str_replace($_GET['title']." ", "", $put2);
	$episode = str_replace(".png", "", $put2);
}
?>
<!DOCTYPE html>
<html lang="ko">
   <head>
      <title><?php echo $_GET[title];?> - <?php echo $episode; ?></title>
      <script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<meta name='viewport' content='initial-scale=1, viewport-fit=cover'>
			<meta name="mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
			<link rel="manifest" href="manifest.json">
			<link rel="icon" type="image/png" href="asset/favicon.png" />
      <link rel="stylesheet" media="all" href="./asset/css/viewer.css">
      <style>
         img{
         display: block;
         }
				 header {
						margin-top: constant(safe-area-inset-top); /* iOS 11.0 */
						margin-top: env(safe-area-inset-top); /* iOS 11.2 */
					}
					footer {
						margin-bottom: constant(safe-area-inset-top); /* iOS 11.0 */
						margin-bottom: env(safe-area-inset-top); /* iOS 11.2 */
					 }
      </style>
   </head>
   <body class="episodes episodes-show">
      <header class="viewer-navigation active">
         <div class="viewer-navigation__wrapper">
            <div class="navigation__title">
							<span class="title__episode"><?php echo $_GET[title];?> - <?php echo $episode; ?></span>
            </div>
         </div>
      </header>
      <section id="content">
         <div id="page-list">
            <p align='center'>
              <?php
							$countloaded = 0;
							if ($type == "zip") {
								foreach ($list as $count) {
	                imgsrc($count);
									$countloaded = $countloaded + 1;
	              }
							} elseif ($type == "png") {
								imgsrc();
								$countloaded = $countloaded + 1;
							}
               ?>
            </p>
         </div>
      </section>
			<?php
         $path = "./".$basefolder."/".$_GET["title"];

         $blacklist = array('');

         $files = preg_grep('/^([^.])/', scandir($path));

				 natsort($files);

         foreach ($files as $file) {
             if (!in_array($file, $blacklist)) {
							 if(strpos($file, "zip") !== false or strpos($file, "cbz") !== false or strpos($file, "png") !== false) {
									 $episodeselect[] = $file;
							 }
             }
         }

         $now = array_search ($_GET["episode"], $episodeselect);

         $next = $now + 1;
         $pre = $now - 1;

         if ($now == '0') {
         $pree = "<li class='empty' id='previous-episodeclass=empty'>< <strong><span>이전화</span></strong></li>";
         } else {
         $pree = "<li style='cursor:pointer;' OnClick=\"location.replace('./viewer.php?title=".urlencode($_GET['title'])."&episode=".urlencode($episodeselect[$pre])."&folder=".$_GET['folder']."')\">< <strong><span title='이전화'>이전화</span></strong></li>";
         }

         if (count($episodeselect) == $next) {
         $nexte = "<li class='empty' id='next-episodeclass=empty'><strong><span>다음화</span></strong> ></li>";
         } else {
         $nexte = "<li style='cursor:pointer;' OnClick=\"location.replace('./viewer.php?title=".urlencode($_GET['title'])."&episode=".urlencode($episodeselect[$next])."&folder=".$_GET['folder']."')\"><strong><span title='다음화'>다음화</span></strong> ></li>";
         }
         ?>
			 <?php if (count($episodeselect) != $next): ?>
				 <script type="text/javascript">
				 $(document).ready(function () {
					 	$.get("./system/preload.php?title=<?php echo urlencode($_GET['title']);?>&episode=<?php echo urlencode($episodeselect[$next]);?>&folder=<?php echo $_GET['folder'];?>", function(data) {});
					});
				 </script>
			 <?php endif; ?>
      <script>
         $(window).scroll(function() {
         	var scrollHeight = $(document).height();
         	var scrollPosition = $(window).height() + $(window).scrollTop();
         	if (scrollPosition > scrollHeight - 120) {
         		if(<?php echo $_COOKIE['auto_next'];?> == 1) {
         			if(<?php echo count($episodeselect); ?> == <?php echo $next; ?>) {
         			alert('다음화가 없습니다!');
         			} else {
         			location.replace("./viewer.php?title=<?php echo urlencode($_GET['title']);?>&episode=<?php echo urlencode($episodeselect[$next]);?>&folder=<?php echo $_GET['folder'];?>");
         			}
         		}
         	}
         });
      </script>
      <footer class="viewer-footer active">
         <div class="footer-wrap">
            <ul class="episode-nav">
               <?php
				  if ($_COOKIE['auto_next'] < '1') {
                  echo "<li style='cursor:pointer;' OnClick=\"location.replace('./cache.php?cache_name=auto_next&cache_value=1&cache_message=정주행 모드가 활성화 되었습니다!')\"><span title='정주행 모드 활성화'><font color='#d4d4d4'><i class='fas fa-magic'></i></font></span></li>";
                  } else {
                  echo "<li style='cursor:pointer;' OnClick=\"location.replace('./cache.php?cache_name=auto_next&cache_value=0&cache_message=정주행 모드가 비활성화 되었습니다!')\"><span title='정주행 모드 비활성화'><font color='#000000'><i class='fas fa-magic'></i></font></span></li>";
                  }
                  ?>
               <li OnClick="location.replace('./index.php')" style='cursor:pointer;'><span title='메인'><i class="fas fa-home"></i></span></li>
               <li OnClick="location.replace('./manga_info.php?title=<?php echo $_GET['title']; ?>&folder=<?php echo $_GET['folder']; ?>#id=<?php $fp = fopen("./metadata/titles/$file/titleid.txt","r"); $fr = fread($fp, filesize("./metadata/titles/$file/titleid.txt")); fclose($fp); echo $fr; ?>')" style='cursor:pointer;'><span title='<?php echo $_GET['title']; ?> 회차 목록'><i class="fas fa-bars"></i></span></li>
               <?php echo $pree;?>
               <?php echo $nexte;?>
            </ul>
         </div>
      </footer>
      <script src="./asset/js/modernizr.js"></script>
      <script src="./asset/js/viewer.js"></script>
			<?php
			if (file_exists("./system/addon/looked.php")) {
				include("./system/addon/lookedgen.php");
			}


			$dir = "./data/temp/";
			$files = array();
			if (is_dir($dir)){
				if ($dh = opendir($dir)){
					while (($file = readdir($dh)) !== false){
						if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
							if (strtotime(date("Y-m-d H:i:s")) - strtotime(date("Y-m-d H:i:s", filemtime("./data/temp/".$file))) >= 3600) {
								unlink("./data/temp/".$file);
							}
						}
					}
					closedir($dh);
				}
			}
			unlink("./data/temp/".$_GET['title'].str_replace('#', '', $_GET['episode']));
			 ?>
			 <?php if ($countloaded == 0): ?>
			 	<meta http-equiv="Refresh" content="1;">
			 <?php endif; ?>
</body>
</html>
