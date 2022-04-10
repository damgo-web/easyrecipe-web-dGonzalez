<?php
include 'config.php';

if (!$conn){
	echo "Failed to connect to database: " . mysqli_connect_error ();
}
if(isset ($_SESSION['userID'])){
	$userID =  $_SESSION['userID'];
///}elseif(isset ($_GET['userID'])){
///	$userID =  $_GET['userID'];
}else{
	header ("Location: register.php");
	exit();
}

$pageTitle = "Profile";

$firstname = NULL;
$lastname = NULL;
$nickname =NULL; 
$username = NULL;
$role=NULL;
$email = NULL;
$password = NULL;
$password_verify = NULL;
$firstname_error = NULL;
$lastname_error  = NULL;
$email_error = NULL;
$password_error = NULL;
$password_match_error  = NULL;
$invalid_image = NULL;
$fileinfo = NULL;
$image_name = NULL;

$valid = TRUE;
$update = FALSE;
$pageContent = NULL;


if (isset($_GET['message'])){
	$message = "<p class='text-danger'> " . $_GET['message'] . "</p>";
}else{
	$message = NULL;
}

if (isset($_GET['action'])){
	$message = "<p class='text-danger'> Record " . $_GET['action'] . "</p>";
}else{
	$message = NULL;
}

if (isset($_GET['update'])){
	$update= TRUE;
}

if (isset($_POST['update'])) {
	$firstname = mysqli_real_escape_string($conn,ucwords(trim($_POST['firstname'])));
	if (empty($firstname)){ 
		$firstname_error = '<span class="text-danger"> - Field Required!</span>';
		$valid = FALSE;
	}
	
	$lastname = mysqli_real_escape_string($conn,ucwords(trim($_POST['lastname'])));	
	if (empty($lastname)){ 
		$lastname_error = '<span class="text-danger"> - Field Required!</span>';
		$valid = FALSE;
	}
	
	$email = (trim($_POST['email']));
	if (empty($email)){ 
		$email_error = '<span class="text-danger"> - Field Required!</span>';
		$valid = FALSE;
	}
	if (!preg_match('/[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/', $email)) {
		$email_error = '<span class="text-danger"> - Invalid email address! email@web.com </span>';
		$valid = FALSE;
	} 
	
	if ($valid){
		$query = "UPDATE `user_table` SET `firstname` = '$firstname', `lastname` = '$lastname', `email` = '$email' WHERE `userID`= $userID;";
		$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
		if (!$result) {
			die(mysqli_error($conn));
		}
	}
	
	$password = trim($_POST['Password']);
	if (!empty ($password)){
		$password_verify = trim($_POST['password_verify']);
		if (strcmp ($password, $password_verify)){
			$password_match_error = '<span class="text-danger"> - Password and Password Verify should match!</span>';
			$valid = FALSE;
		}else{
			///encrypt
			$password = password_hash($password, PASSWORD_DEFAULT);
			$query = "UPDATE `user_table` SET  `Password` = '$password' WHERE `userID`= $userID;";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				die(mysqli_error($conn));
			}else{
				$row_count = mysqli_affected_rows ($conn);
				if ($row_count ==1){
					echo "<p> Record updated </p>";
				}else{
					echo "<p> Password updated failed! </p>";
				}
			}
		}
	}

	if (!empty ($_FILES['profile_image']['name'] )) {
		unlink ("images/" . $_POST ['avatar']);
		$filetype = pathinfo($_FILES['profile_image']['name'],PATHINFO_EXTENSION);
		if ((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and 
		$_FILES['profile_image']['size'] < 300000) {
			if ($_FILES['profile_image']['error'] > 0) {
				$valid = FALSE;
				$file_error = $_FILES["profile_image"]["error"];
				$invalid_image = "<p class ='error'>Return Code:   $file_error <br>";
				switch ($file_error){
				case 1: $invalid_image .= "the file exceed the MAX_FILE_SIZE setting in the page </p>";
					break;
				case 2: $invalid_image .= "the file exceed the MAX_FILE_SIZE setting in the page </p>";
					break;
				default: 
					$invalid_image .= "Something is wrong </p>";
					break;
				}
			} else { 
				$image_name = $_FILES["profile_image"]["name"];
				$file = "images/$image_name";
				$fileinfo = "<p> Upload: $image_name <br>"; 
				$fileinfo .= "Type: " . $_FILES['profile_image']['type'] . "<br>";
				$fileinfo .=  "Size: " . ($_FILES['profile_image']['size'] / 1024) . " Kb <br>";
				$fileinfo .=  "Temp file: " . $_FILES["profile_image"]["tmp_name"] .  "</p>";
				if (file_exists("$file")) {
					$invalid_image = "<span class='error' > $image_name already exists. </span> ";
					$valid = FALSE;
				} else {
					if (move_uploaded_file($_FILES['profile_image']['tmp_name'], "$file" )){
						$fileinfo .= "<p> Your file has been uploaded, as: 	$file</p>";
						///change
						$query = "UPDATE `easy_recipe` SET `avatar`= '$image_name' WHERE `userID`=$userID;";
						$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
						if (!$result) {
							die(mysqli_error($conn));
						} else {
							$row_count = mysqli_affected_rows($conn);
							if ($row_count == 1) {
								echo "<p>Record updated</p>";
							} else {
								echo  "<p>Upload image update failed</p>";
							}
						}	
					} else {
						$invalid_image .= '<span class ="error"> Your image could not be record.</span> ';
					}
				}
			}
		}else {
			$invalid_image  = '<span class = "error"> This is not image. </span> ';
			$valid = FALSE;
		}
	}
}

	$query = "SELECT * FROM `user_table` WHERE `userID`= $userID;";
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
	
if (!$update){
$pageContent .= <<<HERE
<main class="container-fluid">
	<div class= "row">
		
		<div class="col-md-3 col-lg-3 order-md-last bg-1" >
			$message
			
			<h1>Profile</h1>
			<br>
			<h3>$firstname $lastname</h2>

			<figure><img src = "images/$image_name" alt= "Profile image" class="img-responsive img-circle margin" style="display:inline;width:100px;height:100px;">
			</figure>
			
			<p>Nickname:  $nickname</p>
			<p>Email:  $email </p>
			
			<p>Username: <strong> $username</strong></p>
			<p><small>This is your username for future login</small></p>
			<br>
			<p>You are logged in. </p>
			<p><a href="profile.php?update&userID=$userID" class="btn  btn-default"> Update Profile </a></p>
		</div>\n
		
		<div class="col-md-9 col-lg-9 bg-2">
			<h2 class="text-center">My Recipes Selections</h2>
			<p class="text-center">Please select a recipe below.</p>
			<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home">1</a></li>
					<li><a data-toggle="tab" href="#menu1">2</a></li>
					<li><a data-toggle="tab" href="#menu2">3</a></li>
					<li><a data-toggle="tab" href="#menu3">4</a></li>
			</ul>
			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
				<h3><a href="recipes-admin.php?recipeID=1"> First Recipe</a></h3>
				<p></p>
				<p></p>
				</div>
				<div id="menu1" class="tab-pane fade">
				<h3><a  href="recipes-admin.php?recipeID=2"> Second Recipe</a></h3>
				<p></p>
				<p></p>
				</div>
				<div id="menu2" class="tab-pane fade">
				<h3><a href="recipes-admin.php?recipeID=3"> Third Recipe</a></h3>
				<p></p>
				<p></p>
				</div>
				<div id="menu3" class="tab-pane fade">
				<h3><a href="recipes-admin.php?recipeID=4"> Third Recipe</a></h3>
				<p></p>
				<p></p>
				</div>
			</div>
			<hr>
			
			
			<form action="recipes-admin.php" method="post">
			<br><br>
			<br><br>
			<br><br>
			<br><br>
				<div class="form-group">
					<input type="submit" name="edit" value="Create a New Recipe" class="btn btn-primary">
				</div>
			</form>
			<br>
		</div>
	
	</div>
</main>\n
HERE;

}else{	
$pageContent .=<<<HERE
	<main class="container-fluid">
	<div class="jumbotron">
	$message
	<p>Please, update your information. </p>
	<form method="post" enctype="multipart/form-data" action="profile.php">
		<div class="form-group">
			<label for="firstname">First Name: </label>
			<input type="text" placeholder="First Name" name="firstname" id="firstname" value="$firstname" class="form-control">
			$firstname_error
		</div>
		<div class="form-group">
			<label for="lastname">Last Name: </label>
			<input type="text" placeholder="Last Name" name="lastname" id="lastname" value="$lastname" class="form-control">
			$lastname_error
		</div>
		<div class="form-group">
			<label for="email">Email: </label>
			<input type="text" placeholder="example@example.com" name="email" id="email" value="$email" class="form-control">
			$email_error 
		</div>
		<div class="form-group">
			<label for="password">Password: </label>
			<input type="password" placeholder="" name="password" id="password" value="" class="form-control">
			$password_error
		</div>
		<div class="form-group">
			<label for="password_verify">Password Verify: </label>
			<input type="password" placeholder="" name="password_verify" id="password_verify" value="" class="form-control">
			$password_match_error
		</div>
		<figure><img src="images/$image_name" alt= "Profile Avatar" class="profile_image" style="padding:15px;width:220px;height:220px;"/>
		<figcaption>Member:  $firstname  $lastname  </figcaption>
		</figure>
			<p style "clear: both;">Please upload an image for your profile. </p>
			<div class="form-group"> 
			<input type="hidden"  name="MAX_FILE_SIZE"  value="300000" >
			<label for="profile_image">File to Upload: </label> <span class="text-danger" style="font-size:20px;background-color:powderblue;"><br>$invalid_image </span>
			<input type="file" name="profile_image" id="profile_image" class="form-control">
			</div>
			<div class="form-group">
				<input type="hidden" class="btn btn-primary btn-lg" name="avatar" value="$image_name">
				<input type="hidden" class="btn btn-primary btn-lg" name="userID" value="$userID">
				<input type="submit" class="btn btn-success btn-lg" name="update" value="Update Profile">
			</div>
	</form>
	<form method="post" action="update.php">
		<div class="form-group">
			<input type="hidden" class="btn btn-primary btn-lg" name="userID" value="$userID">
			<input type="submit" class="btn btn-danger btn-lg" name="delete" value="Delete Profile">
		</div>
	</form>
	</div>
</main>\n
HERE;
}

include_once 'template.php';
##echo "<pre>";
##print_r ($_POST);
##echo "<pre>";

?>