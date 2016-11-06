<?php
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly", 1);
    session_start();

    //CSRF token
    if ($_SESSION['token'] !== $_POST['token']) {
     die("Request forgery detected");
    }

    // Needed for all MySQL queries
    require 'database.php';

    // QUERY for questions -- order by dates
    // Use a prepared statement  
    $question_stmt = $mysqli->prepare("select questions.id, title, questions, date from questions where id = ?");
    if (!$question_stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Execute, store, and bind result
    $question_stmt->bind_param('i', $id);
    $id = $_POST['question_id'];
    //$id  = 1;
    $question_stmt->execute();
    $question_stmt->store_result();
    $question_stmt->bind_result($id, $title, $question, $datestring);
    $questions_array = array();   
    $question_stmt->fetch(); 

    $questions_array['title'] = $title;
    $questions_array['question'] = $question;
    $questions_array['datestring'] = $datestring;
    $questions_array['success'] = true;
    echo json_encode($questions_array);
    exit;  
?>