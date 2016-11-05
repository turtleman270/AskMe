<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    if (isset($_POST['sessionUserID'])) {
        $_SESSION['userid'] = $_POST['sessionUserID'];
        $_SESSION['token'] = $_POST['sessionToken'];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
        <script src="post_scripts.js"></script>
        <title>Ask Me</title>
    </head>
    <body>

        <div id = "postbody"></div>
        <script>
        <?php
            echo "var token = \"" . $_SESSION['token'] . "\";";
            echo "var userid = \"" . $_SESSION['userid'] . "\";";
        ?>
        // Load assignments upon initial page load
        document.addEventListener("DOMContentLoaded", getPosts, false);
    </script>
        <?php
            
            // // Open div "wrap"
            // echo "<div id=\"wrap\">";
            
            // // Check if session is set -- otherwise, functions limited for guest user
            // if (isset($_SESSION['user_id'])) {
            //     // Open div "header": user and logout info
            //     echo "<div id=\"header\">";               
            //     // Close username query
            //     $username_stmt->close();
            //     // Logout form and Display logged in user
            //     echo "<form action=\"logout.php\" method=\"GET\">";
            //     echo "<label>Logged in as $id <input type=\"submit\" value=\"Log out\" /></label> </form>";
                
            //     // Open div "sidebar": form add stories
            //     echo "<div id=\"sidebar\">";
            //     //Form to add a story
            //     echo "<br><form action=\"addpost.php\" method=\"POST\">";
            //         echo "<strong><label>Ask a Question</label></strong><hr>";
            //         echo "<label>Title: <input type=\"text\" size = 32 name=\"title\" /></label><br>";
            //         echo "<label>Question: </label><br><textarea cols=\"30\" rows=\"9\" name=\"question\"></textarea><br>";
            //         echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
            //         echo "<input type=\"submit\" value=\"Submit\"/>";
            //     echo "</form><br>";
            //     // Close div "sidebar"
            //     echo "</div>";
            // }
            // else {
            //     // Open div "header": guest user 
            //     echo "<div id=\"header\">";
            //     // Display user GUEST
            //     // Form to go back -- nothing to log out, just return to home
            //     echo "<form action=\"home.php\" method=\"POST\">";     
            //         echo "<label>Logged in as GUEST<input type=\"submit\" value=\"Back\"/></label>";
            //     echo "</form>";
            //     // Close div "header"
            //     echo "</div>";
            //     echo "<div id = \"name\">";
            //     echo "QUESTIONS";
            //     echo "</div>";
                
            //     echo "<div id=\"sidebar\">";
            //     echo "Register to Ask Questions!";
            //     // Close div "sidebar"
            //     echo "</div>";
            // }  

            // // QUERY for questions -- order by dates
            // // Use a prepared statement 
            // $question_stmt = $mysqli->prepare("select questions.id, questions.user_id, title, questions, date from questions");
            // if (!$question_stmt) {
            //     printf("Query Prep Failed: %s\n", $mysqli->error);
            //     exit;
            // }
            // // Execute, store, and bind result
            // $question_stmt->execute();
            // $question_stmt->store_result();
            // $question_stmt->bind_result($id, $user_id, $title, $question, $datestring);
            
            // // Open div "main"
            // echo "<div id=\"main\">";
            
            // // Display question info
            // while($question_stmt->fetch()){
                
            //     // Display story info
            //     echo "<h4>" . "<a name=\"$id\">" . htmlspecialchars($title) . "</a>". "</h4>";
            //     echo "Posted by: on " . $datestring;
            //     echo "<br>";
            //     echo htmlspecialchars($question);
            //     echo "<br>";
                
            //     // Form to edit question -- only show if user posted it
            //     if ($_SESSION['user_id'] == $user_id) {
            //         echo "<div class=\"inner_question\">";
            //         echo "<form action=\"posts.php#$id\" method=\"get\">";
            //             echo "<input type=\"hidden\" name=\"id\" value=$id>";
            //             echo "<input type=\"hidden\" name=\"edit\" value=true>";
            //             echo "<input type=\"hidden\" name=\"show\" value=false>";
            //             echo "<input type=\"submit\" value=\"Edit question\">";
            //         echo "</form>";
            //         echo "</div>";
            //     }
                
            //     // If "Edit story" button pressed, show form to edit story
            //     if ($_SESSION['user_id'] == $user_id&&$_GET['id'] == $id && $_GET['edit'] == "true") {
            //         // Editing form
            //         echo "<form action=\"editquestion.php\" method=\"POST\">";
            //             echo "<input type=\"hidden\" name=\"id\" value=$id>";
            //             echo "<label>Title: <input type=\"text\" size = 67 name=\"title\" value=\"$title\" /></label><br>";
            //             echo "<label>Question: </label><br><textarea cols=\"65\" rows=\"3\" name=\"question\">$question</textarea><br>";
            //             echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
            //             echo "<input type=\"submit\" value=\"Submit changes\"/>";
            //         echo "</form>";
                    
            //         // Button to cancel chagnes
            //         echo "<div class=\"inner_question\">";
            //         echo "<form action=\"posts.php#$id\" method=\"get\">";
            //             echo "<input type=\"submit\" value=\"Cancel changes\">";
            //         echo "</form>";
            //         echo "</div>";
            //     }
                
                   
            // }        
            
            
        ?>
        
    </body>
</html>