<?php

    session_start();
    
    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
    
    require 'database.php';
    
    // Use a prepared statement
    $stmt = $mysqli->prepare("insert into likes (user_id, story_id, like_true) values (?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    } 
    // Bind the parameter
    $stmt->bind_param('iis', $user_id, $story_id, $enum);
    $user_id = $_SESSION['user_id'];
    $story_id = $_POST['story_id'];
    $enum = "yes";
    // Execute and close
    $stmt->execute();   
    $stmt->close();
    
    // Redirect to news page
    header("Location: news.php");

?>