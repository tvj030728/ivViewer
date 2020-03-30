<?php
if ($_GET['action'] == 'logout') {
  $logindata = json_decode(file_get_contents('./config.json'), true);
  setcookie('login');
  setcookie($logindata[user]);
  setcookie($logindata[pass]);
  header("Location: ./login/");
}

if (!isset($_POST[user])) {
  die('빈 값');
}
if (!isset($_POST[pass])) {
  die('빈 값');
}

if (!is_dir('./data/')) {
  mkdir('./data/');
}

if (file_exists('config.json')) {
  $value = 0;

  $data_str = file_get_contents('config.json');
  $json = json_decode($data_str, true);

  if ($json['user'] == sha1($_POST['user'])) {
    $value = $value + 1;
  }
  if ($json['pass'] == sha1($_POST['pass'])) {
    $value = $value + 1;
  }
} else {
  if ("ivuser" == $_POST['user']) {
    $value = $value + 1;
  }
  if ("ivpass" == $_POST['pass']) {
    $value = $value + 1;
  }
  $account = array('user'=>sha1('ivuser'), 'pass'=>sha1('ivpass'));
  $myfile = fopen("./config.json", "w") or die("오류발생!");
  fwrite($myfile, json_encode($account, JSON_UNESCAPED_UNICODE));
  fclose($myfile);
}

if ($value == 2) {
  setcookie('login', 'true', time() + 86400 * 30);
  setcookie(sha1($_POST['user']), 'true', time() + 86400 * 30);
  setcookie(sha1($_POST['pass']), 'true', time() + 86400 * 30);
  header("Location: ./");
} else {
  header("Location: ./login/?response=wrong");
}
 ?>
