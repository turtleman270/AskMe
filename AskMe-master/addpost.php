<?php
    session_start();
    
    // CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
        die("Request forgery detected");
    }
 
    // Determine current date and time in CST
    date_default_timezone_set("America/Chicago");
    $timestamp = time();
    $today = getdate($timestamp);
    
    // Turn date into string for query: format YYYY-MM-DD HH:MM:SS
    $datestring = $today['year'] . "-" . $today['mon'] . "-" . $today['mday'] . " " . $today['hours'] . ":" . $today['minutes'] . ":" . $today['seconds'];
    
    require 'database.php';
    
    // Use a prepared statement
    $stmt = $mysqli->prepare("insert into questions (user_id, title, questions, date) values (?, ?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $post_array = array();
    // Bind the parameter
    $stmt->bind_param('isss', $user_id, $title, $question, $datestring);
    $user_id = $_SESSION['user_id'];
    // Execute and close
    $stmt->execute();   
    $stmt->close();
    
    $post_array['success'] = true;
    echo json_encode($post_array);
    exit;  

?>