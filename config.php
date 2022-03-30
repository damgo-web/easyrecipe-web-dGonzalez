<?php
session_start();
/* Contents of config.php */
$conn = mysqli_connect("localhost","root","", "easy_recipe");
//$conn = mysqli_connect("localhost:3306", "Carpenters", "RNQxufzU","Carpenters");

// where $conn is the name you assign to the connection,
// user is the authorized user name (your avatar name),
// password is the password for the user (your server password), and
// database is the default database (your avatar name).


// Set up debug mode	
function debug_data() { // called in template to print arrays at top of any page.
	echo '<pre>SESSION is ';
	echo print_r($_SESSION);
	echo 'COOKIE is ';
	echo print_r($_COOKIE);
	echo 'POST is ';
	echo print_r($_POST);
	echo 'GET is ';
	echo print_r($_GET);
	echo '</pre>';
}
debug_data(); // Comment this out to hide debug information




// authorized function
function auth_user() {
	if(isset($_SESSION['userID'])) {
		return TRUE;
	} else {
		return FALSE;
	}
}

///function easy_recipe-users
function usersPost ($conn, $userID) {
	$stmt = $conn->stmt_init();
	if ($stmt->prepare("SELECT username, firstname, lastname, email, password, image FROM user_table WHERE userID = ?")) {
		$stmt->bind_param("i", $userID);
		$stmt->execute();
		$stmt->bind_result($username, $firstname, $lastname, $email, $password, $image);
		$stmt->fetch();
		$stmt->close();
	}
	$userData = array($username, $firstname, $lastname,  $email, $password, $image);
	return $userData;
}

function Lists($conn) {
	$stmt = $conn->stmt_init();
	if ($stmt->prepare("SELECT userID, username FROM user_table")) {
		$stmt->execute();
		$stmt->bind_result($userID, $username);
		$stmt->store_result();
		$classList_row_cnt = $stmt->num_rows();
		if($classList_row_cnt > 0) { 
			while($stmt->fetch()) {  
				$userData = [$userID => $username ];
				$List_users[] = $userData;
			}
		} else { 
			$userData = [0 => "There are no users at this time."];
			$List_users[] = $userData;
		}
		$stmt->free_result();
		$stmt->close();
	} else { 
		$userData = ["The users access is down now. Please try again later."];
		$List_users[] = $userData;
	}
	return $List_users;
}

///function recipes users
function recipePost($conn, $recipeID) {
	$stmt = $conn->stmt_init();
	if ($stmt->prepare("SELECT recipeTitle, recipeContent FROM recipe_table WHERE recipeID = ?")) {
		$stmt->bind_param("i", $recipeID);
		$stmt->execute();
		$stmt->bind_result($recipeTitle, $recipeContent);
		$stmt->fetch();
		$stmt->close();
	}
	$recipeData = array($recipeTitle, $recipeContent);
	return $recipeData;
}

function recipesPosts($conn) {
	$stmt = $conn->stmt_init();
	if ($stmt->prepare("SELECT recipeID, recipeTitle FROM recipe_table")) {
		$stmt->execute();
		$stmt->bind_result($recipeID, $recipeTitle);
		$stmt->store_result();
		$classList_row_cnt = $stmt->num_rows();
		if($classList_row_cnt > 0) { 
			while($stmt->fetch()) {  
				$recipeData = [$recipeID => $recipeTitle];
				$recipeListData[] = $recipeData;
			}
		} else { 
			$recipeData = [0 => "There are no recipes at this time."];
			$recipeListData[] = $recipeData;
		}
		$stmt->free_result();
		$stmt->close();
	} else { 
		$recipeData = ["The recipes site is down now. Please try again later."];
		$recipeListData[] = $recipeData;
	}
	return $recipeListData;
}

?>