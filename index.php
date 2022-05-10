<?php
//include 'config.php';

if(filter_has_var(INPUT_POST, 'logout')) {
	$msg = 'You are Logged out.';
	foreach ($_SESSION as $field => $value){
		unset($_SESSION[$field]);
	}
	session_destroy();
	header("Location: index.php?msg=$msg");
	exit;
}
if(isset($_GET['msg'])){
	$msg = $_GET['msg'];
	$msg = "<div class='alert alert-danger'>	$msg </div>";
} else {
	$msg = NULL;
}

$pageTitle = "Home";
$pageContent = NULL;

$pageContent .= <<<HERE
<main class= "container-fluid">
$msg

    <div class="row">
		<div class="col-sm-4">
			<h2>Why Sharing Recipes?</h2>
			<div class="fakeimg"><img src="images/food2.jpg" class="img-rounded" alt="Cinque Terre" width="100%" height="auto"> </div>
			<p>Sharing a recipe can be like sharing an intimate memory, one that transcends the table. ... So while recipe sharing speaks to the great human warmth that can be realized at a dinner table, recipe guarding speaks to a fundamental lack of trust. Fortunately, trust is something that can be forged over a shared plate of food.</p>
			
			
			<h1>Recipe Site</h1>
			<ul class="nav nav-pills nav-stacked">
			
			<li class="nav-item">
			<a class="nav-link" href="#">Recipe Gallery</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href="register.php"">Subscribe!</a> 
			</li>
			<li class="nav-item">
			<a class="nav-link disabled" href="#">More..</a>
			</li>
			</ul>
			<hr class="d-sm-none">
		</div>
		<div class="col-sm-8">
			<h2>Did you know?</h2>
			<h5>Esculent Definition, Feb 13, 2022</h5>
			<div class="fakeimg"><img src="images/food1.jpg" class="img-rounded" alt="Cinque Terre" width="100%" height="auto"> </div>
			<p> Did you know?
			One appealing thing about esculent is that this word, which comes from the Latin for food (esca), has been around for over 375 years. If we give you just one more tidbit of etymology-that esca is from Latin edere, which means "to eat"-can you pick which of the following words is NOT related to esculent? Comestible, edacious, edible, escalade, escarole, or obese. Comestible (meaning "edible"), edacious (meaning "voracious"), edible, escarole (a type of salad green), and obese are all descendants of edere. Only escalade (meaning "an act of scaling walls") doesn't belong on the list. It descends from the Italian scalare, meaning "to scale."
			</p>
			<br>
			<h2>Food Sharing!</h2>
			<h5>Recipe Tradition, Feb 13, 2022</h5>
			<div class="fakeimg"><img src="images/food4.jpg" class="img-rounded" alt="Cinque Terre" width="100%" height="auto"></div>
			<p>What is food sharing? ... So sharing around food includes not just sharing food itself, but also the sharing of spaces and even skills around growing, preparing and eating food. Ultimately, for us, food sharing is doing things together around food.</p>
		</div>
	</div>
</main>
HERE;
include 'template.php';
?>