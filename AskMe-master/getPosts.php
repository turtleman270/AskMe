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
    $question_stmt = $mysqli->prepare("select questions.id, questions.user_id, title, questions, date from questions");
    if (!$question_stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Execute, store, and bind result
    $question_stmt->execute();
    $question_stmt->store_result();
    $question_stmt->bind_result($id, $user_id, $title, $question, $datestring);
    $questions_array = array();    
    $cnt = 0;
    
    while($question_stmt->fetch()){

        $questions_array['question' + $cnt]['id'] = $id;
        $questions_array['question' + $cnt]['user_id'] = $user_id;
        $questions_array['question' + $cnt]['title'] = $title;
        $questions_array['question' + $cnt]['question'] = $question;
        $questions_array['question' + $cnt]['datestring'] = $datestring;
        $cnt++;

    }

    $questions_array['success'] = true;
    $questions_array['count'] = $cnt;
    echo json_encode($questions_array);
    exit;  
?>