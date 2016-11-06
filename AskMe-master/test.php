
<?php
    // Needed for all MySQL queries
    require 'database.php';

    // QUERY for questions -- order by dates

    echo $_POST['question_id']
    // Use a prepared statement 
    $question_stmt = $mysqli->prepare("select questions.id, title, questions, date from questions where id = ?");
    if (!$question_stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Execute, store, and bind result
    $question_stmt->bind_param('i', $id);
    $id = 1 ;
    $question_stmt->execute();
    $question_stmt->store_result();
    $question_stmt->bind_result($id, $title, $question, $datestring);
    $questions_array = array();    
    $cnt = 0;
    
    $question_stmt->fetch();
        
       
    exit;  
?>