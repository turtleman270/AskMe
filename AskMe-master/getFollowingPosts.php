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
    $question_stmt = $mysqli->prepare("select id, title, date from questions where id in (select question_id from follow where follow.user_id = ?)");
    if (!$question_stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $question_stmt->bind_param('i', $id);
    $id = $_SESSION['user_id'];
    // Execute, store, and bind result
    $question_stmt->execute();
    $question_stmt->store_result();
    $question_stmt->bind_result($id, $title, $datestring);
    $questions_array = array();    
    $cnt = 0;
    
    while($question_stmt->fetch()){

        $questions_array['question' + $cnt]['id'] = $id;
        $questions_array['question' + $cnt]['title'] = $title;
        $questions_array['question' + $cnt]['datestring'] = $datestring;
        $cnt++;

    }

    $questions_array['success'] = true;
    $questions_array['count'] = $cnt;
    echo json_encode($questions_array);
    exit;  
?>