<?php
    session_start();

    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
 
    require 'database.php';
    
    // Delete entries with foreign key dependency -- comments and likes
    // Delete comments
    $id = $_POST['id'];
    $stmt = $mysqli->prepare("delete from comments where story_id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    
    
    // Delete likes
    $stmt2 = $mysqli->prepare("delete from likes where story_id=?");
    if(!$stmt2){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $stmt2->close();
    
    // Then delete stories
    $stmt3 = $mysqli->prepare("delete from stories where id=?");
    if(!$stmt3){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }  
    $stmt3->bind_param('i', $id);
    $stmt3->execute();
    $stmt3->close();
    
    // Redirect to news page
    header("Location: news.php");

?>