<?php
    session_start();
 
    require 'database.php';
     
    // Use a prepared statement
    $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");   
    // Bind the parameter
    $stmt->bind_param('s', $user);
    $user = $_POST['username'];
    // Execute
    $stmt->execute();
    // Bind and fetch the results
    $stmt->bind_result($cnt, $user_id, $pwd_hash);
    $stmt->fetch();
     
    $pwd_guess = $_POST['password'];
    // Compare the submitted password to the actual password hash
    if ($cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash) {
        // Login succeeded!
        $_SESSION['user_id'] = $user_id;
        $_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string
        // Redirect to news page
        header("Location: news.php");
    } else {
        // Login failed; redirect back to the login screen
        header("Location: home.php?login=failed");
    }
?>