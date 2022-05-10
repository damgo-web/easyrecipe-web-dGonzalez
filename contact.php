<?php

$pageTitle = "Contact Us";
$pageContent = <<<HERE

<h2>Contact Form</h2>
<section  class="container p-5 my-5 bg-light text-secondary rounded">
	
	  <form action="thankyou.php">
		<div class="mb-3">
		<label for="fname">First Name</label>
		<input type="text" id="fname" name="firstname" placeholder="Your name..">
		</div>
		<div class="mb-3">
		<label for="lname">Last Name</label>
		<input type="text" id="lname" name="lastname" placeholder="Your last name..">
		</div>
		<div class="mb-3">
		<label for="country">Country</label>
		<select id="country" name="country">
		  <option value="australia">Australia</option>
		  <option value="canada">Canada</option>
		  <option value="usa">USA</option>
		</select>
		</div>
		<div class="mb-3">
		<label for="subject">Subject</label>
		<textarea id="subject" name="subject" placeholder="Write something.."></textarea>
		</div>
		<div class="mb-3 mt-3">
		<input type="submit" value="Submit">
		</div>
		<div class="mb-3 mt-3">
		<input type="reset" value="Cancel">
		</div>
	  </form>
	  

</section>
HERE;
include 'template.php'
?>
