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
        
        // Check password format
        if (!preg_match('/^[\w_\-]{5,15}+$/', $_POST['password'])) { // Password invalid
            echo "<p><strong>Invalid password: </strong></p>";
            echo "<p>Password must be 3-15 characters and include only alphanumeric characters, -, and _.</p>";
        }
        // Valid user and password, add to users
        else {
            // Use a prepared statement
            $stmt = $mysqli->prepare("insert into users (password) values (?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Bind the parameter
            $stmt->bind_param('s',$pwd_hash);
            $id = $_POST['userid'];
            // Encrypt password
            $pwd_hash = crypt($_POST['password']);
            // Execute and close
            $stmt->execute();
            $stmt->close();
            
            echo "<p><strong>New user added</strong></p>";
        }
        
        // Back button to go to home page
        echo "<form action=\"home.php\" method=\"POST\">";     
                echo "<input type=\"submit\" value=\"Back\"/>";
        echo "</form>";
    ?>
    </body>
</html>
