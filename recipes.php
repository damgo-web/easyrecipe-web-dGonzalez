<?php

include 'config.php';
if(!$conn)  {
   echo "Failed to connect to MySQL: ". mysqli_connect_error();
}

$pageTitle = "My recipes";
$recipeTitle = $recipeContent = $recipeImage = $type = $date = NULL;
$invalid_recipeTitle = $invalid_recipeContent = $invalid_type = $invalid_recipeImage = Null;
$pageContent = $msg =  $valid =  NULL;


if(isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $logged_in = TRUE;
 }else {
    $logged_in= FALSE; 
 }

if(filter_has_var(INPUT_POST, 'edit'))  {
   $edit = TRUE;
}  else  {
   $edit = FALSE;
}


if(filter_has_var(INPUT_POST, 'recipeID'))  {
   $recipeID = filter_input(INPUT_POST, 'recipeID');
}elseif(filter_has_var(INPUT_GET, 'recipeID'))  {
   $recipeID = filter_input(INPUT_GET, 'recipeID');
}else {
   $recipeID = NULL;
}

//check delete Recipe posted
if (filter_has_var(INPUT_POST, 'delete'))  {
   $stmt = $conn->stmt_init();
   if ($stmt->prepare("DELETE FROM `recipe_table` WHERE `recipeID` = ?")){ 
      $stmt->bind_param("i", $recipeID);
      $stmt->execute();
      $stmt->close();
   }
   header ("recipe.php");//route back to home after deletion
   exit();
}

//Recipe Title
if(filter_has_var(INPUT_POST, 'process'))  {
   $valid = TRUE;
   $recipeTitle = mysqli_real_escape_string($conn, trim($_POST['recipeTitle'])); 
   if (empty($recipeTitle))  {
      $invalid_recipeTitle = '<span class="error">Required</span>';
      $invalid = '<span class="error">Title Required</span>';
      $valid = FALSE;
      }
//content
   $recipeContent = mysqli_real_escape_string($conn, trim($_POST['recipeContent'])); 
   if (empty($recipeContent))  {
         $invalid_recipeContent = '<span class="error">Required</span>';
         $invalid = '<span class="error">Content Required</span>';
         $valid = FALSE;
      }
//type
      $type = mysqli_real_escape_string($conn, trim($_POST['type'])); 
   if (empty($type))  {
         $invalid_type = '<span class="error">Required</span>';
         $invalid = '<span class="error">Type Required</span>';
         $valid = FALSE;
      }

//image
   if (!empty($_FILES['recipeImage']['name'])) {
      $imageName = $_FILES["recipeImage"]["name"];
      // unlink("recipeImages/" . $_POST['imageName']);
      $filetype = pathinfo($_FILES['recipeImage']['name'], PATHINFO_EXTENSION);
      if((($filetype == "gif") or ($filetype == "jpg") or ($filetype == "png")) and $_FILES['recipeImage']['size'] < 3000000) {
         if ($_FILES["recipeImage"]["error"] > 0)  {
            $invalid = '<span class="error">Error Free Image Required</span>';
            $valid = FALSE;
            $fileError = $_FILES['recipeImage']['error'];
            $invalid_recipeImage= '<p class= "error"> Return Code: $fileError<br>';
               switch ($fileError)  {
                  case 1:
                     $invalid_recipeImage .= 'The file exceeds upload max size in php.ini.</p>';
                     break;
                  case 2:
                     $invalid_recipeImage .= 'The file exceeds upload max size in HTML form</p>';
                     break;
                  case 3:
                     $invalid_recipeImage .= 'The file was partially uploaded</p>';
                     break;
                  case 4:
                     $invalid_recipeImage .= 'File was NOT uploaded</p>';
                     break;
                  case 5:
                     $invalid_recipeImage .= 'Temporary folder does not exist</p>';
                     break;
                  default:
                     $invalid_recipeImage .= 'Something Unexpected happened.</p>';
                     break;
               }//EO Switch
         } else {
               $file = "recipeImages/$imageName";
               $fileInfo = "<p>Upload: $imageName<br>";
               $fileInfo .= "Type: " . $_FILES["recipeImage"]["type"] . "<br>";
               $fileInfo .= "Size: " . ($_FILES["recipeImage"]["size"] / 1024) . " KB<br>";
               $fileInfo .= "Temp File: " . $_FILES["recipeImage"]["tmp_name"] . "</p>";
               if (move_uploaded_file($_FILES["recipeImage"]['tmp_name'], "$file")) {
                  $fileInfo .= "<p class='text-success'>Your file has been uploaded. Stored as: $file</p>";
                  $stmt = $conn->stmt_init();
                  if ($stmt->prepare("UPDATE `recipe_table` SET `recipeImage` = ? WHERE `recipeID` = ?")) {
                     $stmt->bind_param("si", $imageName, $recipeID);
                     $stmt->execute();
                     $stmt->close();
                  }
               } else {
                  $invalid_recipeImage .='<p><span class="error">Your File could not be uploaded. ';
               }//EO invalid photo else
            }//EO img if
      }/*EO file ext if*/ else {
            $invalid_recipeImage = '<span class= "error">Invalid File. This is not an image.</span>';
            $invalid = '<span class="error">Invalid File</span>';
            $valid = FALSE;
         }//EO invalid file else
   }//EO empty files
   if($valid)  {
      // echo $row_count;
      // echo $query;
      if(filter_has_var(INPUT_POST, 'update'))  {
         $stmt = $conn->stmt_init();
         if ($stmt->prepare("UPDATE `recipe_table` SET `recipeTitle`= ?, `recipeContent`= ?, `type`= ? WHERE `recipeID` = ?")) {
            $stmt->bind_param("sssi", $recipeTitle, $recipeContent, $type, $recipeID);
            $stmt->execute();
            $stmt->close();
         }
      }
    }//else    {
}//EO process
if ($recipeID) {
	$stmt = $conn->stmt_init();
   if ($stmt->prepare("SELECT `recipeTitle`, `recipeContent`, `username`, `recipeImage`, `date`, `type` FROM `recipe_table` WHERE `recipeID` = ?")) {
      $stmt->bind_param("i", $recipeID);
      $stmt->execute();
      $stmt->bind_result($recipeTitle, $recipeContent, $username, $recipeImage, $date, $type);
      $stmt->fetch();
      $stmt->close();
   }
}
if ($edit) {
   $pageContent .= <<<HERE
   <main class="container ml-3 mt-1 bg-light">
      $msg
      <h2 id="myRecipe" class="d-flex justify-content-center">Edit your Recipe Here</h2>
      <form enctype="multipart/form-data" action="recipe.php" method="post">
         <div class="form-group">
            <label for="recipeTitle">Recipe Title</label>
               <input type="text" name="recipeTitle" id="recipeTitle" value="$recipeTitle" placeholder="Recipe Title" class ="form-control" required>$invalid_recipeTitle
         </div>
         <div class="form-group">
            <label for="type">Category</label>
               <input type="text" name="type" id="type" value="$type" placeholder="Breakfast, Lunch, Dinner" class ="form-control" required>$invalid_type
         </div>
         <div class="form-group">
            <label for="recipeContent">Recipe:</label>
               <textarea name="recipeContent" id="recipeContent" class="form-control" rows="5" required>$recipeContent</textarea>$invalid_recipeContent
         </div>
         <p> Please select an image for your recipe.</p>
         <div class="form-group">
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
               <label for="recipeImage">File to Uploads</label> $invalid_recipeImage
               <input type="file" name="recipeImage" id="recipeImage" class="mb-3 form">
         </div>
         <div class= "btn-group">
            <div class="form-group">
               <input type="hidden" name="recipeID" value="$recipeID">
               <input type="hidden" name="process">
               <input type="submit" name="update" value="Update Recipe" class="m-2 btn btn-outline-info">
            </div>
      </form>
         <form action="recipe.php" method="post">
               <div class="form-group">
                  <input type="submit" name="cancel" value="back" class="m-2 btn btn-outline-danger">
               </div>
         </div>
   </main>
HERE;
} elseif ($recipeID) {
	$pageContent .= <<<HERE
   <main class="container ml-3">
      <div class="bg-light">
         <h2 class="d-flex justify-content-end mt-3" id="title">
            $recipeTitle
         </h2>
            <div class="img-fluid">
               <img class="mx-auto d-block" id="recipeImage" src="recipeImages/$recipeImage">
            </div>
         <p class=" h5 m-3">$recipeContent</p>
         <div class="btn-group">
         <form action="recipe.php" method="post">
            <div class="form-group">
               <input type="hidden" name="recipeID" value="$recipeID">
               <input type="submit" name="edit" value="Edit Post" class="m-2 btn btn-outline-info">
            </div>
         </form>
         <form action="recipe.php" method="post">
            <div class="form-group">
               <input type="submit" name="cancel" value="Recipe List" class="m-2 btn btn-outline-warning">
            </div>
         </form>
         <form action="deleteverify.php" method="post">
            <div class="form-group">
               <input type="hidden" name="recipeID" value="$recipeID">
               <input type="submit" name="delete" value="Delete" class="m-2 btn btn-outline-danger">
            </div>
         </form>
         </div>
      </div>
   </main>
HERE;
} else {
// 	select data from db
// 	load default list
   $where = 1;
   $stmt = $conn->stmt_init();
   if ($stmt->prepare("SELECT `recipeID`, `recipeTitle`, `recipeImage` FROM `recipe_table` WHERE ?")) {
      $stmt->bind_param("i", $where);
      $stmt->execute();
      $stmt->bind_result($recipeID, $recipeTitle, $recipeImage);
      $stmt->store_result();
      $classList_row_cnt = $stmt->num_rows();

      if($classList_row_cnt > 0){ // make sure we have at least 1 record
         $selectPost = <<<HERE
         <ul class="list-group list-group-horizontal">
HERE;
         while($stmt->fetch()){ // loop through the result set to build our list
         $selectPost .= <<<HERE
            <li class="list-group-item align-items-stretch  m-2">
            <h3 class="text-center text-capitalize mt-2">
            <a class="text-decoration-none" href="recipe.php?recipeID=$recipeID">$recipeTitle</a>
            </h3>
            <img class="card-img m-2" id="imageThumbnail" src="recipeImages/$recipeImage">
            </li>
HERE;
         }
         $selectPost .= <<<HERE
         </ul>
HERE;
      } else {
         $selectPost = "<p>There are no recipes to see.</p>";
      }
      $stmt->free_result();
      $stmt->close();
   } else {
      $selectPost = "<p>Recipe system is down now. Please try again later.</p>";
   }

   $pageContent .= <<<HERE
   <main class="container ml-3">
      <div class="bg-light">
      <h2 class="d-flex justify-content-center mt-3 pb-2" id="myRecipe">My Recipes</h2>
      $selectPost
      <form action="newRecipe.php" method="post">
      <div class="form-group">
         <input type="submit" name="insert" value="Create New Recipe" class="m-2 btn btn-success">
      </div>
      </form>
   </main>
HERE;
}
include 'recipeTemplate.php';
?>