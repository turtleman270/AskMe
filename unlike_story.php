<?php

    session_start();
    
    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
    
    require 'database.php';
    
    // Use a prepared statement
    $stmt = $mysqli->prepare("delete from likes where user_id=? and story_id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Bind the parameter
    $stmt->bind_param('ii', $user_id, $story_id);
    $user_id = $_SESSION['user_id'];
    $story_id = $_POST['story_id'];
    // Execute and close
    $stmt->execute();
    $stmt->close();
    
    // Redirect to news page
    header("Location: news.php");

?>