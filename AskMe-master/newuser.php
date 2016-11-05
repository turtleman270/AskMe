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
            $stmt = $mysqli->prepare("insert into users (password, age, weight, height, gender, allergies, smoke, alcohol,
    drugs) values (?,?,?,?,?,?,?,?,?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Bind the parameter
            $stmt->bind_param('siddsssss',$pwd_hash, $age, $weight,$height,$gender,$allergies, $smoke, $alcohol,$drugs);
            // Encrypt password
            $pwd_hash = crypt($_POST['newpassword']);
            // Execute and close
            $stmt->execute();
            $stmt->close();
            
            echo "<p><strong>New user added</strong></p>";
        }

        // Use a prepared statement
        $stmt = $mysqli->prepare("SELECT id FROM users ORDER BY id DESC LIMIT 1");
        
        // Execute
        $stmt->execute();
        // Bind the results
        $stmt->bind_result($user_id);
        //set the session id
        $_SESSION["user_id"] = $user_id;
        // Fetch and close
        $stmt->fetch();
        $stmt->close();

        $assignments_array['success'] = true;
        $assignments_array['id'] = $user_id;
        
        echo json_encode($assignments_array);
        exit;

?>
