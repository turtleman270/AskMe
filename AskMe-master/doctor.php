<?php
	function like(){
		session_start();


    	// CSRF token
    	//if ($_SESSION['token'] !== $_POST['token']) {
    	//    die("Request forgery detected");
    	//}

    	$doctorId = $_SESSION['doctorId'];
    	$answerId = $_POST['replyId'];

	$mysqli = new mysqli('localhost', 'archhack', 'archhack', 'ASKME');

   	    // Use a prepared statement
	    $stmt = $mysqli->prepare("Select likes from replies where id = 1");
	    if(!$stmt){
	        printf("Query Prep Failed: %s\n", $mysqli->error);
	        exit;
    } 	

	    // Use a prepared statement
	    $stmt = $mysqli->prepare("update likes (questionId, title, story_text, story_link, date) values (?, ?, ?, ?, ?)");
	    if(!$stmt){
	        printf("Query Prep Failed: %s\n", $mysqli->error);
	        exit;
    }

	}
?>
