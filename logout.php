<?php
include 'config.php';

	session_destroy();
	header("Location: index.php?msg=You are logged out.");
	exit;


?>