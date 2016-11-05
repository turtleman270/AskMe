<?php
    session_start();

    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
    
    require 'database.php';
    
    // Use a prepared statement
    $comment_id = $_POST['commentid'];
    $stmt = $mysqli->prepare("delete from comments where id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Bind the parameter
    $stmt->bind_param('i', $comment_id);
    // Execute and close
    $stmt->execute();
    $stmt->close();
    
    // Redirect to news page
    header("Location: news.php");

?>