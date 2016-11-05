<?php
    session_start();
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly", 1);
 
    require 'database.php';
     
    // Use a prepared statement
    $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE id=?");   
    // Bind the parameter
    $stmt->bind_param('i', $id);
    $id = $_POST['userid'];
    // Execute
    $stmt->execute();
    // Bind and fetch the results
    $stmt->bind_result($cnt, $id, $pwd_hash);
    $stmt->fetch();
     
    $pwd_guess = $_POST['password'];
    // Compare the submitted password to the actual password hash
    if ($cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash) {
        // Login succeeded!
        $_SESSION['user_id'] = $id;
        $_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string
        // Redirect to news page
        echo json_encode(array(
            "success" => true,
            "token" => $_SESSION['token'],
            "id" => $id
        ));
        exit;
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Incorrect Userid or Password"
        ));
        exit;
    }
?>