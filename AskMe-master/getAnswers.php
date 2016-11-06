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
    $question_stmt = $mysqli->prepare("select * from replies where question_id = ?");
    if (!$question_stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Execute, store, and bind result
    $question_stmt->bind_param('i', $id);
    $id = $_POST['question_id'];
    // Execute, store, and bind result

    $question_stmt->execute();
    $question_stmt->store_result();
    $question_stmt->bind_result($id, $doctor_id, $likes, $reply);
    $questions_array = array();    
    $cnt = 0;
    
    while($question_stmt->fetch()){

        $questions_array['question' + $cnt]['id'] = $id;
        $questions_array['question' + $cnt]['doctor_id'] = $doctor_id;
        $questions_array['question' + $cnt]['likes'] = $likes;
        $questions_array['question' + $cnt]['reply'] = $reply;
        // $questions_array['question' + $cnt]['datestring'] = $datestring;
        $cnt++;

    }

    $questions_array['success'] = true;
    $questions_array['count'] = $cnt;
    echo json_encode($questions_array);
    exit;  
?>