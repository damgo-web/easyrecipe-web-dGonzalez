<?php

session_start();
/* Contents of config.php */
//$conn = mysqli_connect("localhost","root","", "easy_recipe");
$conn = mysqli_connect("localhost:3306", "Carpenters", "RNQxufzU","Carpenters");

// where $conn is the name you assign to the connection,
// user is the authorized user name (your avatar name),
// password is the password for the user (your server password), and
// database is the default database (your avatar name).


// Set up debug mode	

/*function debug_data() { // called in template to print arrays at top of any page.
	echo '<pre>SESSION is ';
	echo print_r($_SESSION);
	echo 'COOKIE is ';
	echo print_r($_COOKIE);
	echo 'POST is ';
	echo print_r($_POST);
	echo 'GET is ';
	echo print_r($_GET);
	echo '</pre>';
}
debug_data(); // Comment this out to hide debug information*/



// authorized function
function auth_user() {
	if(isset($_SESSION['userID'])) {
		return TRUE;
	} else {
		return FALSE;
	}
}



?>