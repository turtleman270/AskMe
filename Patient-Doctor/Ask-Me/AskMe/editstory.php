<?php
    session_start();

    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
    
    require 'database.php';
    
    // Use a prepared statement
    $id = $_POST['id'];
    $title = $_POST['title'];
    $story_text = $_POST['story'];
    $story_link = $_POST['link'];
    $stmt = $mysqli->prepare("update stories set title=?, story_text=?, story_link=? where id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Bind the parameter
    $stmt->bind_param('sssi', $title, $story_text, $story_link, $id);
    // Execute and close
    $stmt->execute();
    $stmt->close();
    
    // Redirect to news page
    header("Location: news.php");

?>