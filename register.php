<?php
include 'config.php';

if (!$conn){
	echo "Failed to connect to database: " . mysqli_connect_error ();
}

$pageTitle = "Registration";
$valid = TRUE;
$logged_in = FALSE;



$firstname = NULL; 
$lastname = NULL; 
$username = NULL; 

$firstname_error = NULL;
$lastname_error  = NULL;
$nickname_error = NULL;
$email = NULL; 
$email_error = NULL;
$password = NULL; $password_verify = NULL;
$password_error = NULL;
$password_match_error  = NULL;
$invalid_image = NULL;
$fileinfo = NULL;
$image_name = NULL;

$pageContent = NULL;
$message =NULL;

//Submit Form 

if (isset($_POST['submit'])) {
	

	$firstname = mysqli_real_escape_string($conn,ucwords(trim($_POST['firstname'])));
	if (empty($_POST['firstname'])){ 
		$firstname_error = '<span class="text-danger"> - Field Required!</span>';
		$valid = FALSE;
	}
	
	$lastname = mysqli_real_escape_string($conn,ucwords(trim($_POST['lastname'])));	
	if (empty($_POST['lastname'])){ 
		$lastname_error = '<span class="text-danger"> - Field Required!</span>';
		$valid = FALSE;
	}
	
	
	$username= strtolower(substr ($firstname, 0,1).$lastname);
	
	
	$email = mysqli_real_escape_string($conn, trim($_POST['email']));
	if (empty($_POST['email'])){ 
		$email_error = '<span class="text-danger"> - Field Required!</span>';
		$valid = FALSE;
	}
	if (!preg_match('/[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/', $email)) {
		$email_error = '<span class="text-danger"> - Invalid email address! email@web.com </span>';
		$valid = FALSE;
	} 
		
	$password = mysqli_real_escape_string($conn, trim($_POST['password']));
	if (empty($_POST['password'])){ 
		$password_error = '<span class="text-danger"> - Field Required!</span>';
		$valid = FALSE;
	}
	if (!preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/',$password)) {
		$password_error = '<span class="text-danger"> - Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters</span>';
		$valid = FALSE;
	} 
	
	$password_verify = (trim($_POST['password_verify']));
	if (strcmp ($password, $password_verify)!==0){
			$password_match_error = '<span class="text-danger"> - Password and Password Verify should match!</span>';
			$valid = FALSE;
	}

	$password = trim(filter_input(INPUT_POST, 'password'));

//Avatar Image

	if ($valid) {
		$filetype = pathinfo($_FILES['profile_image']['name'],PATHINFO_EXTENSION);
		if ((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and 
		$_FILES['profile_image']['size'] < 3000000) {
			if ($_FILES['profile_image']['error'] > 0) {
				$valid = FALSE;
				$file_error = $_FILES['profile_image']['error'];
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
				$image_name = $_FILES['profile_image']['name'];
				$file = "images/$image_name";
				$fileinfo = "<p> Upload: $image_name <br>"; 
				$fileinfo .= "Type: " . $_FILES['profile_image']['type'] . "<br>";
				$fileinfo .=  "Size: " . ($_FILES['profile_image']['size'] / 1024) . " Kb <br>";
				$fileinfo .=  "Temp file: " . $_FILES['profile_image']['tmp_name'] . "<br>";
				if (file_exists("$file")) {
					$invalid_image = "<span class='error' > $image_name already exists. </span> ";
					$valid = FALSE;
				} else {
					if (move_uploaded_file($_FILES['profile_image']['tmp_name'], "$file" )){
						$fileinfo .= "<p> Your file has been uploaded, as: 	$file</p>";
						///encrypt
						$password = password_hash($password, PASSWORD_DEFAULT);
						///change
						$query = "INSERT INTO `user_table` VALUES (DEFAULT,'1','$firstname','$lastname','$username','$email','$password', '$image_name');";
						$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
						if (!$result) {
							die(mysqli_error($conn));
						} else {
							$row_count = mysqli_affected_rows($conn);
							if ($row_count == 1) {
								$userID = mysqli_insert_id($conn);
								//header ("Location: profile.php");
								//exit();
								$logged_in = TRUE;
								$message = "<p>Record inserted</p>";
							} else {
								$message= "<p>Insert failed</p>";
							}
						}			
					} else {
						$invalid_image .= '<span class ="error"> Your image could not be record.</span> ';
					}
				}
			}
		}else {
			$invalid_image = '<span class = "error"> This is not image. </span> ';
			$valid = FALSE;
		}
	}
}

if ($logged_in){
	$query = "SELECT * FROM  `user_table` WHERE `userID` = $userID;";
	$result = mysqli_query($conn, $query);
	if (!$result) {
		die(mysqli_error($conn));
	} 
	if ($row = mysqli_fetch_assoc($result)){
		//set database field values
		$firstname = $row ['firstname'];
		$lastname = $row ['lastname'];
		$username = $row ['username'];
		$email = $row ['email'];
		$image_name = $row ['avatar'];
	}else{
		$message= "oops! Sorry, we could not find matching credentials ";
	}

$pageContent .= <<<HERE
	<br>
	<section  class="container p-5 my-5 bg-light text-secondary rounded">

	
		$message
		<h1> Welcome, $firstname  $lastname </h1>
		<figure><img src = "images/$image_name" alt= "Profile image" class="profile_image" style="style="float:left;width:120px;height:120px;">

		</figure>
		<p><a href="profile.php?userID=$userID"> View Profile </a></p>
		<p>Email:  $email </p>
		<p>You are logged in. </p>
		<p>This is your username for future login</p>
		<p> Username: <strong> $username</strong></p>

	<section>\n
HERE;

}else{		
	if (isset ($_GET['action'])){
		$message = "<p class='text-danger'> Record " . $_GET['action'] . "</p>";
	}

$pageContent .=<<<HERE
<div class="container p-5 my-5 bg-light text-secondary rounded">
	
		<fieldset>
		<legend> Easy Recipe Registration </legend>
		<form method="post" enctype="multipart/form-data" action="register.php">

			<div class="mb-3 mt-3">
				<label for="firstname">First Name: </label>
				<input type="text" placeholder="First Name" name="firstname" id="firstname" value="$firstname" class="form-control">$firstname_error
			</div>
			
			<div class="mb-3 mt-3">
				<label for="lastname">Last Name: </label>
				<input type="text" placeholder="Last Name" name="lastname" id="lastname" value="$lastname" class="form-control">$lastname_error
			</div>
			
			<div class="mb-3 mt-3">
				<label for="email">Email: </label>
				<input type="text" placeholder="example@example.com" name="email" id="email" value="$email" class="form-control">$email_error 
			</div>
					
			<div class="mb-3 mt-3">
				<label for="password">Password: </label>
				<input type="password" placeholder="" name="password" id="password" value="" class="form-control">$password_error
			</div>
					
			<div class="mb-3 mt-3">
				<label for="password_verify">Password Verify: </label>
				<input type="password" placeholder="" name="password_verify" id="password_verify" value="" class="form-control">$password_match_error
			</div>
				
			<p>For new registration, please upload an image </p>
			<div class="mb-3 mt-3"> 
			<input type="hidden"  name="MAX_FILE_SIZE"  value="300000" >
			<label for="profile_image">File to Upload: </label> <span class="text-danger" style="font-size:20px;background-color:powderblue;"><br>$invalid_image </span>
			<input type="file" name="profile_image" id="profile_image" class="form-control">
			</div>
			<div class="mb-3 mt-3">
				<button class="btn btn-primary " type="submit" name="submit" value="Submit ">Submit Application</button>
				<button class="btn btn-danger " type="submit" name="reset" value="Reset ">Reset</button>
			</div>
		</form>
		</fieldset>\n

</div>
HERE;
}

include_once 'template.php';

?>