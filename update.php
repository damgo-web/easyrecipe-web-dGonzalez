<?php
include 'config.php';

if (!$conn){
	echo "Failed to connect to database: " . mysqli_connect_error ();
}
if (isset ($_POST['userID'])){
	$userID =  $_POST['userID'];
}else{
	header ("Location: register.php");
	exit();
}

$pageTitle = "My Profile";
$firstname = NULL;
$lastname = NULL;
$email = NULL;
$username = NULL;
$image_name = NULL;
$nickname= NULL;

$message = NULL;
$pageContent = NULL;


if (isset($_POST['delete_profile'])){
	$query = "DELETE FROM `user_table` WHERE `userID` = $userID LIMIT 1;";
	$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	if (!$result) {
		$message = "<p> Deleted failed </p>";
	}else{
		$row_count = mysqli_affected_rows ($conn);
		if ($row_count ==1){
			unlink ("images/" . $_POST ['avatar']);
			header ("Location: register.php?action=delete");
			exit();
		}else{
			$message = "<p>Insert Failed</p>";
		}
	}
}

	$query = "SELECT * FROM `user_table` WHERE `userID` = $userID;";
	$result = mysqli_query($conn, $query);
	if (!$result) {
		die(mysqli_error($conn));
	}
	if ($row = mysqli_fetch_assoc ($result)){
		$firstname = $row ['firstname'];
		$lastname = $row ['lastname'];
		$username = $row ['username'];
		$nickname= $row ['nickname'];
		$email = $row ['email'];
		$image_name = $row ['avatar'];
	}else{
		$message = "Sorry, we could not find you record.";
	}
	


$pageContent .= <<<HERE
	<section class="container">
		<h1> Delete User Account </h1>
		<p class="alert alert-danger"> Are you sure want to delete this member account? This action cannot be undone!</p>
	$message
	<figure><img src = "images/$image_name" alt= "Profile image" class="profile_image" style="float:left;padding:15px;width:220px;height:220px;"/>
		<figcaption> Member:  $firstname  $lastname </figcaption>
	</figure>
	<p>Email:  $email </p>
	<p>Username: <strong> $username</strong></p>
	<p>Nickname:<strong> $nickname</strong></p>
	<form method="post" action="profile.php">
		<div class="form-group">
			<input type="hidden" class="btn btn-primary btn-lg" name="userID" value="$userID">
			<input type="submit" class="btn btn-secondary btn-lg" name="profile" value="Cancel">
		</div>
	</form>
	<form method="post" action="update.php">
		<div class="form-group">
			<input type="hidden" class="btn btn-primary btn-lg" name="avatar" value="$image_name">
			<input type="hidden" class="btn btn-primary btn-lg" name="userID" value="$userID">
			<input type="submit" class="btn btn-danger btn-lg" name="delete_profile" value="Delete Profile">
		</div>
	</form>
	<section>\n
HERE;


include_once 'template.php';


?>