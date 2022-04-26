<!DOCTYPE html>
<html lang="en">
     <head >
        <title>Easy Recipes</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
        <!--FontAwesome kit-->
            <script src="https://kit.fontawesome.com/c0e800fc4a.js" crossorigin="anonymous"></script>
            
        <!--External Style sheet-->
            <link rel="stylesheet"  type="text/css" href="index.css">
        <!-- favicon -->
            <link rel="icon" type="image/png" sizes="32x32" href="images\logo.png">
        <!--header font from google-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
    </head>
<body>
	<header class="hero">
       <h1>Easy Recipies</h1>
     </header>


	<nav class="navbar navbar-expand-sm navbar-dark sticky-top">
		<div class="container-fluid">
			<img  class="navbar-brand" src="images/logo.png"  id="logo" alt="Page Logo" width="50" height="50">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNav" aria-controls="myNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="myNav">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="index.php">Home</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="profile.php">Profile</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="recipes.php">Recipes </a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="recipes.php">Gallery</a>
					</li>
					<li class="nav-item">
					<a class="nav-link disabled">About Us</a>
					</li>
					<li class="nav-item">
					<a class="nav-link disabled">Contact us</a>
					</li>
				</ul>
				<form class="d-flex">
					<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
					<button id="button" class="btn btn-outline-primary btn-sm" type="submit" >Search</button>
				</form>
			</div>
		</div>
	</nav>
<?php
print $pageContent;
?>
	<footer class="container-fluid">
	<br>
	<?php
//button function
$loginButton =NULL;
if(isset ($_SESSION['userID'])){
     $loginButton = <<<HERE
                    <form class="form-inline" action="logout.php" method="post">
                        <button class="btn btn-primary btn-sm" name="logout" type="submit">Log Out</button>
                    </form> 
HERE;
} else  {
     $loginButton = <<<HERE
                        <form class="form-inline" action="login.php" method="post" >
                          <button class="btn btn-primary btn-sm" type="submit">Sign In </button>
                        </form>
HERE;
}

print $loginButton;
?>

	
					<a href="register.php" class= > Subscribe in our Recipes WebSite </a>		
					<hr>	
					<p> Developed by Yordin Kirk, Semhar Bire, Damaris Gonzalez</p>
					<a href="https://www.youtube.com/" target="_blank"><span class="fab fa-youtube-square" ></span></a>
					<a href="https://www.instagram.com/" target="_blank"><span class="fab fa-instagram" ></span></a>
					<a href="https://www.pinterest.com/" target="_blank"><span class="fab fa-pinterest-square"></span></a>
					<a href="https://www.twitter.com/" target="_blank"><span class="fab fa-twitter-square"></span></a>
					<br>		
					
					<p>© BHC Web Dev 2022</p>
	</footer>

</body>

</html>
