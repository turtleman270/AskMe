<?php
    session_start();

        require 'database.php';
        
        // Check password format
        if (!preg_match('/^[\w_\-]{5,15}+$/', $_POST['newpassword'])) { // Password invalid
            echo "<p><strong>Invalid password: </strong></p>";
            echo "<p>Password must be 3-15 characters and include only alphanumeric characters, -, and _.</p>";
        }
        // Valid user and password, add to users
        else {
            // Use a prepared statement
            $stmt = $mysqli->prepare("insert into users (password, userid) values (?, ?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Bind the parameter
            $stmt->bind_param('ss',$pwd_hash, $userid);
            // Encrypt password
            $pwd_hash = crypt($_POST['newpassword']);
            $userid = crypt(1);
            // Execute and close
            $stmt->execute();
            $stmt->close();
        }

        // Use a prepared statement
        $stmt = $mysqli->prepare("SELECT id, userid FROM users ORDER BY id DESC LIMIT 1");
        
        // Execute
        $stmt->execute();
        // Bind the results
        $stmt->bind_result($id, $user_id);
        //set the session id
        $_SESSION["user_id"] = $id;
        // Fetch and close
        $stmt->fetch();
        $stmt->close();

        $newuser_array['success'] = true;
        $newuser_array['id'] = $id;
        $newuser_array['userid'] = $user_id;
        
        echo json_encode($newuser_array);
        exit;

?>
