<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
        <title>New user</title>
    </head>
    <body>

    <?php

        require 'database.php';
        
        // Use a prepared statement
        $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");
        // Bind the parameter
        $stmt->bind_param('s', $user);
        $user = $_POST['username'];
        // Execute
        $stmt->execute();
        // Bind the results
        $stmt->bind_result($cnt, $user_id, $pwd_hash);
        // Fetch and close
        $stmt->fetch();
        $stmt->close();
        
        
        //Check if user already exists
        if($cnt == 1){
            echo "<p><strong>User already exists</strong></p>";
        }  
        else {
            // Check username format
            if(!preg_match('/^[\w_\-]{3,15}+$/', $user) ){ // Username is not valid format
                echo "<p><strong>Invalid user: </strong></p>";
                echo "<p>Username must be 3-15 characters and include only alphanumeric characters, -, and _.</p>";   
            }
            else if (strtolower($user) == "guest") { // Username is guest
                echo "<p><strong>Invalid user: </strong></p>";
                echo "<p>Cannot create user named GUEST</p>";
            }
            // Check password format
            else if (!preg_match('/^[\w_\-]{5,15}+$/', $_POST['password'])) { // Password invalid
                echo "<p><strong>Invalid password: </strong></p>";
                echo "<p>Password must be 3-15 characters and include only alphanumeric characters, -, and _.</p>";
            }
            // Valid user and password, add to users
            else {
                // Use a prepared statement
                $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                // Bind the parameter
                $stmt->bind_param('ss', $user, $pwd_hash);
                $user = $_POST['username'];
                // Encrypt password
                $pwd_hash = crypt($_POST['password']);
                // Execute and close
                $stmt->execute();
                $stmt->close();
                
                echo "<p><strong>New user added</strong></p>";
            }
        }
        
        // Back button to go to home page
        echo "<form action=\"home.php\" method=\"POST\">";     
                echo "<input type=\"submit\" value=\"Back\"/>";
        echo "</form>";
    ?>
    </body>
</html>
