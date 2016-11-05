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
    $stmt = $mysqli->prepare("insert into stories (user_id, title, story_text, story_link, date) values (?, ?, ?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Bind the parameter
    $stmt->bind_param('issss', $user_id, $title, $story_text, $story_link, $datestring);
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $story_text = $_POST['story'];
    $story_link = $_POST['link'];
    // Execute and close
    $stmt->execute();   
    $stmt->close();
    
    // Redirect to news page
    header("Location: news.php");

?>