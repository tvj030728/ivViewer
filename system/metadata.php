<?php
$logindata = json_decode(file_get_contents('../config.json'), true);
$logindatauser = $logindata[user];
$logindatapass =$logindata[pass];
if ($_COOKIE['login'] == true) {}else{
	die(header("Location: ../login/"));
}
if (!isset($_COOKIE[$logindatauser])) {
	die(header("Location: ../login/"));
}
if (!isset($_COOKIE[$logindatapass])) {
	die(header("Location: ../login/"));
}

if (!is_dir('./temp/')) {
  mkdir('./temp/');
}

$action = $_GET['action'];

if ($action == 'start') {
  $dir = "../data/";
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){
        if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir" || $file == "temp") { continue; } else {
          mkdir("./temp/".$file);
          $continue[] = $file;
        }
      }
      closedir($dh);
    }
  }
  header("Location: ./metadata-creator.php?folder=".$continue[0]);
}

if ($action == 'continue') {
  rmdir("./temp/".$_GET['finish']);
  $dir = "./temp/";
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){
        if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir" || $file == "temp") { continue; } else {
          $continue[] = $file;
        }
      }
      closedir($dh);
    }
  }
  if (isset($continue[0])) {
    header("Location: ./metadata-creator.php?folder=".$continue[0]);
  } else {
    rmdir("./temp/");
    header("Location: ../?response=metafin");
  }
}
 ?>
