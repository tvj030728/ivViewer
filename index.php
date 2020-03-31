<?php
if (!is_dir('./system/setting/')) {
	mkdir('./system/setting/');
}

if (is_dir("./system/setting/metaload")) {
	include('./system/page/metaload.php');
} else {
	include('./system/page/dataload.php');
}

?>
