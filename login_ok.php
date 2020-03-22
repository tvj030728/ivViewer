<?php
if ($_GET['action'] == 'logout') {
  setcookie('login');
  header("Location: ./login/");
}

if (!isset($_POST[user])) {
  die('빈 값');
}
if (!isset($_POST[pass])) {
  die('빈 값');
}
include('config.php');

$value = 0;

$data_str = file_get_contents('config.json');
$json = json_decode($data_str, true);

if ($account_id == $json['user']) {
  $value = $value + 1;
}
if ($account_pw == $json['pass']) {
  $value = $value + 1;
}

if ($value == 2) {
  setcookie('login', 'true', time() + 86400 * 30);
  header("Location: ./");
} else {
  header("Location: ./login/?response=wrong");
}
 ?>
