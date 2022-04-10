<?php
include 'config.php';

if (!$conn){
	echo "Failed to connect to database: " . mysqli_connect_error ();
}

$pageTitle = "My Recipies";
$recipeContent = NULL;
$pageContent = NULL;




if (filter_has_var(INPUT_GET, 'recipeID')) {
	$recipeID = filter_input(INPUT_GET, 'recipeID');
	$recipeData = recipePost($conn, $recipeID);
	$recipeTitle = $recipeData[0];
	$recipeContent = $recipeData[1];
	$pageContent = "<h2>$recipeTitle</h2>
	$recipeContent
	$image_name
	\n
	<p><a href='recipes.php'>Back to My Recipes</a></p>";

} else {
	$recipeList = "<ul>";
	$recipeListData = recipesPosts($conn);
	foreach ($recipeListData as $recipePost) {	
		foreach ($recipePost as $recipeID => $recipeTitle) {
			if($recipeID == 0) {
				$recipeList  = <<<HERE
			<p>$recipeTitle</p>
HERE;
			} else {
				$recipeList .= <<<HERE
				<li><a href="recipes.php?recipeID=$recipeID">$recipeTitle</a></li>
HERE;
			}
		}
	}
	$recipeList .= "</ul>";
	$pageContent = <<<HERE
	<section class="container">
	<div class="jumbotron">
	
		<h2>My Recipes</h2>
		<p>Please select a recipe below.</p>
		<br>
		$recipeList
		<br>
	</div>
	</section>
HERE;
}


include_once 'template.php';

?>