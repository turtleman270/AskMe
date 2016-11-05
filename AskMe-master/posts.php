<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
        <title>Ask Me</title>
    </head>
    <body>
        <?php
            // Prepare CSRF token
            $token = $_SESSION['token'];

            // Needed for all MySQL queries
            require 'database.php';
            
            // QUERY for username based on user id of logged-in user
            // Use a prepared statement
            $username_stmt = $mysqli->prepare("SELECT id FROM users WHERE id=?");
            if (!$username_stmt) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Bind the parameter
            $username_stmt->bind_param('i', $user_id);
            $user_id = $_SESSION['user_id'];
            // Execute
            $username_stmt->execute();
            // Bind the results
            $username_stmt->bind_result($id);
            // Fetch
            $username_stmt->fetch();

            echo $user_id;
            
            // Open div "wrap"
            echo "<div id=\"wrap\">";
            
            // Check if session is set -- otherwise, functions limited for guest user
            if (isset($_SESSION['user_id'])) {
                // Open div "header": user and logout info
                echo "<div id=\"header\">";               
                // Close username query
                $username_stmt->close();
                // Logout form and Display logged in user
                echo "<form action=\"logout.php\" method=\"GET\">";
                echo "<label>Logged in as $id <input type=\"submit\" value=\"Log out\" /></label> </form>";
                
                // Open div "sidebar": form add stories
                echo "<div id=\"sidebar\">";
                //Form to add a story
                echo "<br><form action=\"addpost.php\" method=\"POST\">";
                    echo "<strong><label>Ask a Question</label></strong><hr>";
                    echo "<label>Title: <input type=\"text\" size = 32 name=\"title\" /></label><br>";
                    echo "<label>Question: </label><br><textarea cols=\"30\" rows=\"9\" name=\"question\"></textarea><br>";
                    echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                    echo "<input type=\"submit\" value=\"Submit\"/>";
                echo "</form><br>";
                // Close div "sidebar"
                echo "</div>";
            }
            else {
                // Open div "header": guest user 
                echo "<div id=\"header\">";
                // Display user GUEST
                // Form to go back -- nothing to log out, just return to home
                echo "<form action=\"home.php\" method=\"POST\">";     
                    echo "<label>Logged in as GUEST<input type=\"submit\" value=\"Back\"/></label>";
                echo "</form>";
                // Close div "header"
                echo "</div>";
                echo "<div id = \"name\">";
                echo "QUESTIONS";
                echo "</div>";
                
                echo "<div id=\"sidebar\">";
                echo "Register to Ask Questions!";
                // Close div "sidebar"
                echo "</div>";
            }          
            
            
        ?>
        
    </body>
</html>