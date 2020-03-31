<?php
if (file_exists("./system/setting/metaload.iv")) {
	include('./system/page/metaload.php');
} else {
	include('./system/page/dataload.php');
}
?>
