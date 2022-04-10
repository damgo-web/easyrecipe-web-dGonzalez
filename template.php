<?php

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
    </head>
<body>
	<header>
			<img class="img-responsive" src="images/hero2.png"  alt="Cinque Terre" >
	</header>
	<nav class="navbar" data-spy="affix" data-offset-top="700" >

			<div class="navbar-header">
			<button  type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php" style="color:white; font-weight: bold;">Home</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav" >
				<li><a href="profile.php" > Profile </a> </li>
				<li><a href="recipes-admin.php" > Recipes </a> </li> 
				<li><a href="#" >Gallery </a> </li>
				<li><a href="#" >About Us</a> </li>
				<li><a href="#"	>Contact Us</a></li>
					</ul>
			</ul>
			</div>

		<div>
	</nav>
    
HERE;
print $pageContent;
echo<<<HERE
	<footer class="container-fluid">
	<br>
					<a  href="login.php" >
					<button type="button"  class="btn" style="background-color:#00758f; color:white;"> Sign In </button>
					</a>
					
	<br>
	<hr>	
					<p> Developed by Yordin Kirk, Semhar Bire, Damaris G.</p>
					<a href="https://www.youtube.com/" class="btn"><span class="fab fa-youtube-square" style="font-size:52px;color:red"></span></a>
					<a href="https://www.instagram.com/" class="btn"><span class="fab fa-instagram" style="font-size:52px;color:fuchsia"></span></a>
					<a href="https://www.pinterest.com/" class="btn"><span class="fab fa-pinterest-square" style="font-size:52px;color:firebrick"></span></a>
					<a href="https://www.twitter.com/" class="btn"><span class="fab fa-twitter-square" style="font-size:52px;color:DeepSkyBlue"></span></a>
	<br>		
					<a href="register.php" > Subscribe in our Recipes WebSite </a>
			
					
					<p>© BHC Web Dev 2022</p>
	 </footer>

</body>

</html>
HERE;
?>
