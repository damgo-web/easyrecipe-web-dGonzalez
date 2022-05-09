<?php

$pageTitle = "Home";
$pageContent = <<<HERE
<h2>Our Team</h2>
<div class="row">
  <div class="column">
    <div class="card">
      <img src="images/jordan.jpg" alt="Jordan" class="profilePic">
      <div class="container">
        <h2>Jordan Camacho</h2>
        <p class="title">Web Developer</p>
        <p>Some text that describes me lorem ipsum ipsum lorem.</p>
        <p>jordan@example.com</p>
        <p><button class="bg-dark text-white"><a href="contactus.php">Contact</a></button></p>
      </div>
    </div>
  </div>
  <div class="column">
    <div class="card">
      <img src="images/demaris.png" alt="Damaris" class="profilePic">
      <div class="container">
        <h2>Damaris Gonzalez</h2>
        <p class="title">Web Developer</p>
        <p>As Business Administrator and after having experience in selling technology solutions, I was wondering about how can apply my knowledge to generate new ideas and business models. It was when I decided to study Web Production and Design studies; and then I was aware what about my taste over food, restaurants, recipies and overall everything related to good cooking. What about if we have a fancy and nice place where cooking easily? Here our recipe site 'Easy Recipes' was born.</p>
        <p>demaris@example.com</p>
        <p><button class="bg-dark text-white"><a href="contactus.php">Contact</a></button></p>
      </div>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <img src="images/semhar.png" alt="Semhar" class="profilePic">
      <div class="container">
        <h2>SemharBire</h2>
        <p class="title">Web Developer</p>
        <p>My name is Semhar and I am a Web Developer. I am very passionate about Web Development, and strive to better myself as a developer, and the development community as a whole. The recipe site you are currently viewing is a group project for our web programming project course. This project enables us to convey our knowledge on languages like HTML/HTML5, CSS/CSS3, PHP and SQL/MySQL. The objective of this recipe website is providing useful cooking content on a website which allow establishing trust with new subscribers, who would creating an account and regularly adding new information for each section, tips, reviews, comments, and active participating in help forum with recommendations. And building authority, prestige, and a subscriber base which allows achieve readers engagement for future marketing strategies on the website.   </p>
        <p>semhar@example.com</p>
        <p><button class="bg-dark text-white"><a href="contactus.php">Contact</a></button></p>
      </div>
    </div>
  </div>
</div>
</body>
HERE;

include_once 'template.php';

?>