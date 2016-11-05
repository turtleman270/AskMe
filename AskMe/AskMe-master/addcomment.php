<?php
    session_start();
    
    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
    
    require 'database.php';
    
    // Use a prepared statement
    $stmt = $mysqli->prepare("insert into comments (user_id, story_id, comment_text) values (?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }  
    // Bind the parameter
    $stmt->bind_param('iis', $user_id, $story_id, $comment_text);
    $user_id = $_SESSION['user_id'];
    $story_id = $_POST['id'];
    $comment_text = $_POST['comment'];
    // Execute and close
    $stmt->execute();
    $stmt->close();
    
    // Redirect to news page and show comments for story
    header("Location: news.php?id=$story_id&show=true");

?>