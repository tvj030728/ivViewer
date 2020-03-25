<?php
$basefolder = "../data/naver/";

if(strpos($_GET['episode'], "zip") !== false) {
  copy($basefolder."/".$_GET['title']."/".$_GET['episode'], "../data/temp/".$_GET['title'].$_GET['episode']);
} else {
 if(strpos($_GET['episode'], "png") !== false) {
   copy($basefolder."/".$_GET['title']."/".$_GET['episode'], "../data/temp/".$_GET['title'].$_GET['episode']);
 } else {
   die("옳바르지 않은 파일 타입 입니다.");
 }
}
 ?>
