<?php

$loginButton =NULL;
if(isset ($_SESSION['userID'])){
     $loginButton = <<<HERE
                    <form class="form-inline" action="logout.php" method="post">
                        <button class="btn primary"  name="logout" type="submit">Log Out</button>
                    </form> 
HERE;
} else  {
     $loginButton = <<<HERE
                        <form class="form-inline" action="login.php" method="post" >
                          <button class="btn primary" type="submit">Sign In </button>
                        </form>
HERE;
}

echo<<<HERE
<!DOCTYPE html>
<html lang="en">
    <head >
        <title>Easy Recipes</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha-long-hash_code" crossorigin="anonymous">
	   <!-- internal css -->
        <link rel="stylesheet" href="style.css">
		<!-- favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="images\logo.png">
    </head>
<body>
	<header>
			<img class="img-responsive" src="images/hero2.png"  alt="Cinque Terre" >
	</header>
	<nav class="navbar" data-spy="affix" data-offset-top="680" >
		<div class="navbar-header">
			<button  type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
			</button>
			<img class="navbar-brand" src="images/logo.png" id="logo" alt="Page Logo">
			<a href="index.php" style="color:white; font-weight: bold;">Home</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar" >
			<ul class="nav navbar-nav"  >
				<li><a href="profile.php" > Profile </a> </li>
				<li><a href="recipes-admin.php" > Recipes </a> </li> 
				<li><a href="#" >Gallery </a> </li>
				<li><a href="#" >About Us</a> </li>
				<li><a href="#"	>Contact Us</a></li>
			</ul>
			<form class="navbar-form navbar-right" action="/action_page.php">
				<div class="input-group">
						<input type="text" class="form-control" placeholder="Search" name="search">
					<div class="input-group-btn">
						<button class="btn btn-default" type="submit">
							<i class="glyphicon glyphicon-search"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
		 
	</nav>
HERE;
print $pageContent;


echo<<<HERE
	<footer class="container-fluid">
	<br>
	$loginButton
					<a href="register.php" class= > Subscribe in our Recipes WebSite </a>		
					<hr>	
					<p> Developed by Yordin Kirk, Semhar Bire, Damaris Gonzalez</p>
					<a href="https://www.youtube.com/" class="btn"><span class="fab fa-youtube-square" style="font-size:52px;color:red"></span></a>
					<a href="https://www.instagram.com/" class="btn"><span class="fab fa-instagram" style="font-size:52px;color:fuchsia"></span></a>
					<a href="https://www.pinterest.com/" class="btn"><span class="fab fa-pinterest-square" style="font-size:52px;color:firebrick"></span></a>
					<a href="https://www.twitter.com/" class="btn"><span class="fab fa-twitter-square" style="font-size:52px;color:DeepSkyBlue"></span></a>
					<br>		
					
					<p>© BHC Web Dev 2022</p>
	 </footer>

</body>

</html>
HERE;
?>