<?php
    session_start();
    
    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
    require 'database.php';
    
    // Use a prepared statement
    $stmt = $mysqli->prepare("insert into follow (user_id, question_id) values (?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $post_array = array();
    // Bind the parameter
    $stmt->bind_param('ss', $user_id, $question_id);
    $user_id = $_SESSION['user_id'];
    $question_id = $_POST['question_id'];
    // Execute and close
    $stmt->execute();   
    $stmt->close();
    
    $post_array['success'] = true;
    echo json_encode($post_array);
    exit;  
?>