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

if ($account_id == $_POST['user']) {
  $value = $value + 1;
}
if ($account_pw == $_POST['pass']) {
  $value = $value + 1;
}

if ($value == 2) {
  setcookie('login', 'true', time() + 86400 * 30);
  header("Location: ./");
} else {
  header("Location: ./login/?response=wrong");
}
 ?>
