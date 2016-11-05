<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
        <title>Newsroom</title>
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
                    echo "<label>Question: </label><br><textarea cols=\"30\" rows=\"9\" name=\"story\"></textarea><br>";
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
            
            // QUERY for questions -- order by dates
            // Use a prepared statement 
            $question_stmt = $mysqli->prepare("select questions.id, questions.user_id, title, question, date,
                                        from questions
                                        group by questions.id
                                        order by date desc");
            if (!$question_stmt) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Execute, store, and bind result
            $question_stmt->execute();
            $question_stmt->store_result();
            $question_stmt->bind_result($id, $user_id, $title, $question, $datestring);
            
            // Open div "main"
            echo "<div id=\"main\">";
            
            // Display question info
            while($question_stmt->fetch()){
                
                // QUERY for user who posted question
                $user_stmt = $mysqli->prepare("SELECT id FROM users WHERE id=?");
                if (!$user_stmt) {
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $user_stmt->bind_param('i', $user_id);
                $user_stmt->execute();
                $user_stmt->store_result();
                $user_stmt->bind_result($userid);
                $user_stmt->fetch();
                $user_stmt->close();
                
                // Display story info
                echo "<h4>" . "<a name=\"$id\">" . htmlspecialchars($title) . "</a>". "</h4>";
                echo "Posted by: on " . $datestring;
                echo "<br>";
                echo htmlspecialchars($question);
                echo "<br>";
                
                // Form to edit question -- only show if user posted it
                if ($_SESSION['user_id'] == $user_id) {
                    echo "<div class=\"inner_question\">";
                    echo "<form action=\"posts.php#$id\" method=\"get\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"edit\" value=true>";
                        echo "<input type=\"hidden\" name=\"show\" value=false>";
                        echo "<input type=\"submit\" value=\"Edit question\">";
                    echo "</form>";
                    echo "</div>";
                }
                
                // If "Edit story" button pressed, show form to edit story
                if ($_SESSION['user_id'] == $user_id&&$_GET['id'] == $id && $_GET['edit'] == "true") {
                    // Editing form
                    echo "<form action=\"editquestion.php\" method=\"POST\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<label>Title: <input type=\"text\" size = 67 name=\"title\" value=\"$title\" /></label><br>";
                        echo "<label>Question: </label><br><textarea cols=\"65\" rows=\"3\" name=\"question\">$question</textarea><br>";
                        echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                        echo "<input type=\"submit\" value=\"Submit changes\"/>";
                    echo "</form>";
                    
                    // Button to cancel chagnes
                    echo "<div class=\"inner_question\">";
                    echo "<form action=\"posts.php#$id\" method=\"get\">";
                        echo "<input type=\"submit\" value=\"Cancel changes\">";
                    echo "</form>";
                    echo "</div>";
                }
                
                // Form to delete story -- only show if user posted it
                if ($_SESSION['user_id'] == $user_id) {
                    echo "<div class=\"inner_question\">";
                    echo "<form action=\"delete_question.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                        echo "<input type=\"submit\" value=\"Delete question\">";
                    echo "</form>";
                    echo "</div>";
                }
                
                   
            }
                
                // Button to show replies for question
                if (!($_GET['id'] == $id && $_GET['show'] == "true")) {
                    //$action = "/~kstathis/news.php#$id"; // Self-submitting form
                    $action = "/~tinani/AskMe/posts.php#$id";
                    echo "<div class=\"inner\">"; 
                    echo "<form action=\"$action\" method=\"get\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"show\" value=true>";
                        echo "<input type=\"submit\" value=\"Show replies\">";
                    echo "</form>";
                    echo "</div>";
                }

                // If "Show replies" button pressed, show replies
                if (isset($_GET['id'])) {
                    // QUERY for story's comments
                    $reply_stmt = $mysqli->prepare("select id, user_id, question_id, reply from replies where question_id=?");
                    if (!$reply_stmt) {
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $reply_stmt->bind_param('i', $question_id);
                    $question_id = $_GET['id'];
                    $reply_stmt->execute();
                    $reply_stmt->store_result();
                    $reply_stmt->bind_result($reply_id, $reply_doctor_id, $reply_question_id, $reply);
                    
                    // Display the comments for the story
                    if ($id == $question_id && $_GET['show'] != "false") {
                        // Button to hide comments
                        //$action = "/~kstathis/news.php"; //Self-submitting
                         $action = "/~tinani/AskMe/posts.php";
                        echo "<div class=\"inner_question\">"; 
                        echo "<form action=\"$action#$id\" method=\"get\">";
                            echo "<input type=\"hidden\" name=\"show\" value=false>";
                            echo "<input type=\"submit\" value=\"Hide comments\">";
                        echo "</form>";
                        echo "</div>";
                        echo "<br>";
                        
                        while($reply_stmt->fetch()) {
                            // Query for doctors who replies question
                            $reply_user_stmt = $mysqli->prepare("SELECT id FROM doctors WHERE id=?");
                            if (!$reply_user_stmt) {
                                printf("Query Prep Failed: %s\n", $mysqli->error);
                                exit;
                            }
                            $reply_user_stmt->bind_param('i', $reply_doctor_id);
                            $reply_user_stmt->execute();
                            $reply_user_stmt->bind_result($reply_doctor_id);
                            $reply_user_stmt->fetch();
                            $reply_user_stmt->close();
                            
                            // Display user who posted and comment text
                            echo htmlspecialchars($reply_doctor_id) . ": ";
                            echo htmlspecialchars($reply);
                            echo "<br>";
                            
                            // Form to edit comment -- only show if user posted it
                            if ($_SESSION['user_id'] == $comment_user_id) {
                                echo "<div class=\"inner_comment\">"; 
                                echo "<form action=\"$action#$id\" method=\"get\">";
                                    echo "<input type=\"hidden\" name=\"commentid\" value=$comment_id>";
                                    echo "<input type=\"hidden\" name=\"commentedit\" value=true>";
                                    echo "<input type=\"hidden\" name=\"id\" value=$id>";
                                    echo "<input type=\"hidden\" name=\"show\" value=true>";
                                    echo "<input type=\"hidden\" name=\"commentedit\" value=true>";
                                    echo "<input type=\"submit\" value=\"Edit comment\">";
                                echo "</form>";
                                echo "</div>";
                            }
                            // If "Edit comment" button pressed, show form to edit comment
                            if ($_SESSION['user_id'] == $comment_user_id&&$_GET['commentid'] == $comment_id && $_GET['commentedit'] == "true") {
                                // Editing form
                                echo "<form action=\"editcomment.php\" method=\"POST\">";
                                    echo "<label>Revise comment: </label><br><textarea cols=\"65\" rows=\"3\" name=\"comment\">$comment_text</textarea><br>";
                                    echo "<input type=\"hidden\" name=\"commentid\" value=$comment_id>";
                                    echo "<input type=\"hidden\" name=\"storyid\" value=$id>";
                                    echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                                    echo "<input type=\"submit\" value=\"Submit changes\"/>";
                                echo "</form>";
                                
                                // Button to cancel changes
                                echo "<div class=\"inner_comment\">"; 
                                echo "<form action=\"news.php#$id\" method=\"GET\">";
                                    echo "<input type=\"hidden\" name=\"id\" value=$id>";
                                    echo "<input type=\"hidden\" name=\"show\" value=true>";
                                    echo "<input type=\"submit\" value=\"Cancel changes\">";
                                echo "</form>";
                                echo "</div>";
                            }
                            // Form to delete comment -- only show if user commented it
                            if ($_SESSION['user_id'] == $comment_user_id) {
                                echo "<div class=\"inner_comment\">"; 
                                echo "<form action=\"delete_comment.php\" method=\"post\">";
                                    echo "<input type=\"hidden\" name=\"commentid\" value=$comment_id>";
                                    echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                                    echo "<input type=\"hidden\" name=\"story_id\" value=\"$id\"/>";
                                    echo "<input type=\"submit\" value=\"Delete comment\">";
                                echo "</form>";
                                echo "</div>";
                                echo "<br>";
                            }
                        }
                        
                        // Form to add a comment -- only if user is logged in  
                        if (isset($_SESSION['user_id'])) {
                            echo "<form action=\"addcomment.php\" method=\"POST\">";
                                echo "<label>Add comment: </label><br><textarea cols=\"65\" rows=\"5\" name=\"comment\"></textarea><br>";
                                echo "<input type=\"hidden\" name=\"id\" value=$id>";
                                echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                                echo "<input type=\"submit\" value=\"Submit\"/>";
                            echo "</form>";
                        }
                    }
                    
                    $comment_stmt->close();
                }
                // Line in between stories
                echo "<hr>";
            }
             
            $story_stmt->close();
            // Close div "main"
            echo "</div>";
            // Close div "wrap"
            echo "</div>";
        ?>
        
    </body>
</html>