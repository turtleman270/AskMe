<?php
    session_start();

    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
    
    require 'database.php';
    
    // Use a prepared statement
    $id = $_POST['commentid'];
    $comment_text = $_POST['comment'];

    $stmt = $mysqli->prepare("update comments set comment_text=? where id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Bind the parameter
    $stmt->bind_param('si', $comment_text, $id);
    // Execute and close
    $stmt->execute();
    $stmt->close();
    
    // Redirect to news page and show comments
    $story_id = $_POST['storyid'];
    header("Location: news.php?id=$story_id&show=true");

?>