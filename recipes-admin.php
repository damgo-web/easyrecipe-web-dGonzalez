<?php
include 'config.php';


if(auth_user()) {
	$userID=$_SESSION ['userID'];
}else{
	header("location: recipes.php");
	exit();
}


if (!$conn){
	echo "Failed to connect to database: " . mysqli_connect_error ();
}

$pageTitle = "My Recipies";
$pageContent = $message= NULL;
$recipeContent = $recipeTitle = $selectRecipe= $image_name = NULL;
$invalid_title = $invalid_content = $invalid_image = NULL;
$content=NULL;
$classList_row_cnt=NULL;

if (filter_has_var(INPUT_POST, 'edit')) {
    $edit = TRUE;
} else {
    $edit = FALSE;
}


if (filter_has_var(INPUT_POST, 'recipeID')) {
    $recipeID = filter_input(INPUT_POST, 'recipeID');
} elseif (filter_has_var(INPUT_GET, 'recipeID')) {
    $recipeID = filter_input(INPUT_GET, 'recipeID');
} else {
    $recipeID = NULL;
}

if (filter_has_var(INPUT_POST, 'delete'	)){
	$stmt = $conn->stmt_init();
	if ($stmt->prepare ("DELETE FROM `recipe_table` WHERE `recipeID` =?")){
		$stmt->bind_param("i", $recipeID);
		$stmt->execute();
		$stmt->close();
	}
	header ("Location: recipes-admin.php");
	exit();
}
	
if (filter_has_var(INPUT_POST, 'process')){
	$valid=TRUE;
	$recipeTitle=mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'recipeTitle')));
	if (empty ($recipeTitle)){
			$invalid_title = '<span class="error"> Field Required</span>';
			$valid=FALSE;
	}
	$recipeContent=mysqli_real_escape_string($conn, trim(filter_input(INPUT_POST, 'recipeContent')));
	if (empty ($recipeContent)){
		$invalid_content ='<span class="error"> Field Required</span>';
		$valid=FALSE;
	}
	if (empty ($_FILES['recipe_image']['name'] )) {
		unlink ("images/" . $_POST ['image']);
		$filetype = pathinfo($_FILES['recipe_image']['name'],PATHINFO_EXTENSION);
		if ((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and 
		$_FILES['recipe_image']['size'] < 3000000) {
			if ($_FILES['recipe_image']['error'] > 0) {
				$valid = FALSE;
				$file_error = $_FILES["recipe_image"]["error"];
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
				$image_name = $_FILES["recipe_image"]["name"];
				$file = "images/$image_name";
				$fileinfo = "<p> Upload: $image_name <br>"; 
				$fileinfo .= "Type: " . $_FILES['recipe_image']['type'] . "<br>";
				$fileinfo .=  "Size: " . ($_FILES['recipe_image']['size'] / 1024) . " Kb <br>";
				$fileinfo .=  "Temp file: " . $_FILES["recipe_image"]["tmp_name"] .  "</p>";
				if (file_exists("$file")) {
					$invalid_image = "<span class='error' > $image_name already exists. </span> ";
					$valid = FALSE;
				} else {
					if (move_uploaded_file($_FILES['recipe_image']['tmp_name'], "$file" )){
						$fileinfo .= "<p> Your file has been uploaded, as: 	$file</p>";
						///change
						$query = "UPDATE `recipe_table` SET `image`= '$image_name' WHERE `recipeID`=$recipeID;";
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
	
	if ($valid){
		
		if (filter_has_var(INPUT_POST, 'insert'	)){
			$stmt = $conn->stmt_init();
			if($stmt->prepare ("INSERT INTO `recipe_table` (`recipeTitle`, `recipeContent`, `authorID`, `image`)VALUES(?,?,?,?)")){
				$stmt->bind_param("ssis", $recipeTitle, $recipeContent, $userID, $image_name);
				$stmt->execute();
				$stmt->close();
				$message="Created!";
				$recipeID=mysqli_insert_id($conn);
				header("Location: recipes-admin.php?recipeID=$recipeID");
				exit();
				}
		}
		if(filter_has_var(INPUT_POST, 'update'	)){
			$stmt = $conn->stmt_init();
			if($stmt->prepare ("UPDATE `recipe_table` SET `recipeTitle`=?, `recipeContent`=?  `image` =? WHERE `recipeID`=?")){
				$stmt->bind_param("ssis", $recipeTitle, $recipeContent, $recipeID, $image_name);
				$stmt->execute();
				$stmt->close();
				header ("Location: recipes-admin.php?recipeID=$recipeID");
				exit();
				}
		}
	}
}	
if ($recipeID){
	$stmt = $conn->stmt_init();
	if ($stmt->prepare("SELECT `recipeTitle`, `recipeContent`, ` image` FROM `recipe_table` WHERE `recipeID`=?")) {
		$stmt->bind_param("i", $recipeID);
		$stmt->execute();
		$stmt->bind_result($recipeTitle, $recipeContent, $image_name);
		$stmt->fetch();
		$stmt->close();
}

$button =<<<HERE
	<div class="form-group">
		<input type="hidden" name="recipeID" value="$recipeID">
		<input type="hidden" name="process">
		<input type="submit" name="update" value="Update Recipe" class="btn btn-success">
	</div>
HERE;
}else{
$button =<<<HERE
	<div class="form-group">
		<input type="hidden" name="process">
		<input type="submit" name="insert" value="Save Recipe" class="btn btn-success">
	</div>
HERE;
}

if ($edit){
$pageContent.= <<<HERE
	<section  class="container">
	<div class="jumbotron">
		$message
		<p> Please Complete the Form</p>
		<form action="recipes-admin.php" enctype="multipart/form-data" method="post">
			<div class="mb-3 mt-3">
			<div class="form-group">
				<label for="recipeTitle"> Title: </label><br>
				<input type="text" placeholder="A recipe title " name="recipeTitle" id="recipeTitle" value="$recipeTitle" class="form control"> $invalid_title
			</div>
			<div class="form-group" >
			<label for="content">Content: </label><br>
			<textarea name="recipeContent"  id="recipeContent" class="form control" id="instructions" style="height: 100px;"> $recipeContent</textarea>$invalid_content
			 <label for="instructions">Starting with an introduction, then ingredients and the step by step...</label>
			</div>
			
			<div class="form-group">
			<input type="hidden"  name="MAX_FILE_SIZE"  value="3000000" >
			<label for="recipe_image"> Recipe Image</label><span class="text-danger" style="font-size:20px;background-color:powderblue;"><br>$invalid_image </span>
				<input type="file" name="recipe_image" id="recipe_image" class="form-control">
			</div>\n
			</div>
			$button
		</form>
		<form action="recipes-admin.php" method="post">
			<div class="form-group">
			<input type="submit" name="cancel" value="Show Recipes List" class="btn btn-primary">
			</div>
		</form>
	</div>
	</section>\n
HERE;
}elseif($recipeID){
	$pageContent = <<<HERE
	<section  class="container">
		<div class="jumbotron">
			<h2> Recipes Site </h2>
			<h3> $recipeTitle</h3>
			<p> $recipeContent</p>
			<figure><img src = "images/$image_name" alt= "Recipe image" class="profile_image" style="style="float:left;width:120px;height:120px;">
			</figure>
			<p>
			<form action="recipes-admin.php" method="post">
				<div class="form-group">
				<input type="hidden" name="recipeID" value="$recipeID">
				<input type="submit" name="edit" value="Edit Recipe" class="btn btn-success">
				</div>
			</form>
			<br>
			<form action="recipes-admin.php" method="post">
				<div class="form-groupp">
				<input type="submit" name="cancel" value="Show Recipes List" class="btn btn-primary">
				</div>
			</form>
			<br>
			<form action="recipes-admin.php" method="post">
				<input type="hidden" name="recipeID" value="$recipeID">
				<div class="form-group">
					<input type="submit" name="delete" value="Delete Recipe" class="btn btn-danger">
				</div>
			</form>
		</div>
	</section>
	
HERE;
}else{
	$where=1;
	$stmt = $conn->stmt_init();
	if($stmt->prepare ("SELECT `recipeID` ,`recipeTitle`, `recipeContent`,`image` FROM `recipe_table`")){
		//$stmt->bind_param("i", $userID);
		$stmt->execute();
		$stmt->bind_result($recipeID, $recipeTitle, $recipeContent, $image_name);
		$stmt->store_result();
		$classList_row_cnt=$stmt->num_rows();

		if ($classList_row_cnt>0){
			$selectRecipe.=<<<HERE
			<ul>\n
HERE;
			while($stmt->fetch()){
				$selectRecipe.=<<<HERE
				<li><a href="recipes-admin.php?recipeID=$recipeID">$recipeTitle</a></li>\n
				<ul>\n
HERE;
			}
			$selectRecipe .=<<<HERE
			</ul>\n
HERE;
		}else{
			$selectRecipe .="<p> There recipes available</p>";
		}
		$stmt->free_result();
		$stmt ->close();
	}else{
		$selectRecipe .= "<p> Recipes Site is down. Please try later</p>";
	}

	$pageContent = <<<HERE
	<section  class="container">
		<div class="jumbotron">
		<h2>My Recipes Selections</h2>
		<p>Please select a recipe below.</p>
		$selectRecipe
		<br>
		<form action="recipes-admin.php" method="post">
			<div class="form-group">
				<input type="submit" name="edit" value="Create a New Recipe" class="btn btn-success">
			</div>
		</form>
		</div>
	</section>
	
HERE;
}

include_once 'template.php';
?>