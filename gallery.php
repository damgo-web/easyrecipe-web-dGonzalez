<?php
// configuration
include 'config.php';
if(!$conn)  {
   echo "Failed to connect to MySQL: ". mysqli_connect_error();
}
// sticky's
$pageTitle = "Gallery";
$pageContent = NULL;
$recipeTitle  = $recipeImage = $recipeID = Null;

// QUERY
$sql = "SELECT `recipeID`, `recipeTitle`, `recipeImage` FROM recipe_table";
$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));			
while( $record = mysqli_fetch_assoc($resultset) ) {
}

//get information from db load default list
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
         <li class="list-group-item align-items-stretch shadow p-4 m-2 bg-light w-25">
         <h3 class="text-center text-capitalize mt-2">
         <a class="text-decoration-none" id="g-text" href="recipe.php?recipeID=$recipeID">$recipeTitle</a>
         </h3>
         <img class="card-img mx-auto d-block" id="gallery-img" src="recipeImages/$recipeImage">
         </li>
HERE;
         }// class="card-img-top shadow p-4 mb-4 bg-light" class="card p-4 mb-4"
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
      <h2 class="d-flex justify-content-center mt-3 pb-4" id="myRecipe">Recipe Gallery</h2>
      $selectPost
   </main>
HERE;


include 'template.php';
?>

