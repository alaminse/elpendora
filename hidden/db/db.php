<?php
	$db = mysqli_connect('localhost', 'root', '', 'elpandora');
	if (!$db) {
		header("location:../404.php");
	}
?>