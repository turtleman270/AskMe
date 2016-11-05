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
            $username_stmt = $mysqli->prepare("SELECT username FROM users WHERE id=?");
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
            $username_stmt->bind_result($username);
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
                echo "<label>Logged in as $username <input type=\"submit\" value=\"Log out\" /></label> </form>";
              
                // Close div "header"
                echo "</div>";
                echo "<div id = \"name\">";
                echo "NEWSROOM";
                echo "</div>";
                
                // Open div "sidebar": form add stories
                echo "<div id=\"sidebar\">";
                //Form to add a story
                echo "<br><form action=\"addstory.php\" method=\"POST\">";
                    echo "<strong><label>ADD A STORY</label></strong><hr>";
                    echo "<label>Title: <input type=\"text\" size = 32 name=\"title\" /></label><br>";
                    echo "<label>Story text: </label><br><textarea cols=\"30\" rows=\"9\" name=\"story\"></textarea><br>";
                    echo "<label>Link  (optional): <input type=\"text\" size = 32 name=\"link\" /></label><br>";
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
                echo "NEWSROOM";
                echo "</div>";
                
                echo "<div id=\"sidebar\">";
                echo "Register to ADD A STORY!";
                // Close div "sidebar"
                echo "</div>";
            }          
            
            // QUERY for stories -- order by number of likes
            // Use a prepared statement 
            $story_stmt = $mysqli->prepare("select stories.id, stories.user_id, title, story_text, story_link, date,
                                        count(likes.like_true) as total_likes
                                        from stories left join likes on (stories.id=likes.story_id)
                                        group by stories.id
                                        order by total_likes desc");
            if (!$story_stmt) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Execute, store, and bind result
            $story_stmt->execute();
            $story_stmt->store_result();
            $story_stmt->bind_result($id, $user_id, $title, $story_text, $story_link, $datestring, $count_total_likes);
            
            // Open div "main"
            echo "<div id=\"main\">";
            
            // Display story info
            while($story_stmt->fetch()){
                
                // QUERY for user who posted story
                $user_stmt = $mysqli->prepare("SELECT username FROM users WHERE id=?");
                if (!$user_stmt) {
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $user_stmt->bind_param('i', $user_id);
                $user_stmt->execute();
                $user_stmt->store_result();
                $user_stmt->bind_result($username);
                $user_stmt->fetch();
                $user_stmt->close();
                
                // Display story info
                echo "<h4>" . "<a name=\"$id\">" . htmlspecialchars($title) . "</a>". "</h4>";
                echo "Posted by: " . htmlspecialchars($username) . " on " . $datestring;
                echo "<br>";
                echo htmlspecialchars($story_text);
                echo "<br>";
                echo "<a href=$story_link>$story_link</a>";
                echo "<br>";
                
                // Form to edit story -- only show if user posted it
                if ($_SESSION['user_id'] == $user_id) {
                    echo "<div class=\"inner_story\">";
                    echo "<form action=\"news.php#$id\" method=\"get\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"edit\" value=true>";
                        echo "<input type=\"hidden\" name=\"show\" value=false>";
                        echo "<input type=\"submit\" value=\"Edit story\">";
                    echo "</form>";
                    echo "</div>";
                }
                
                // If "Edit story" button pressed, show form to edit story
                if ($_SESSION['user_id'] == $user_id&&$_GET['id'] == $id && $_GET['edit'] == "true") {
                    // Editing form
                    echo "<form action=\"editstory.php\" method=\"POST\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<label>Title: <input type=\"text\" size = 67 name=\"title\" value=\"$title\" /></label><br>";
                        echo "<label>Story text: </label><br><textarea cols=\"65\" rows=\"3\" name=\"story\">$story_text</textarea><br>";
                        echo "<label>Link  (optional): <input type=\"text\" size = 67 name=\"link\" value=\"$story_link\" /></label><br>";
                        echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                        echo "<input type=\"submit\" value=\"Submit changes\"/>";
                    echo "</form>";
                    
                    // Button to cancel chagnes
                    echo "<div class=\"inner_story\">";
                    echo "<form action=\"news.php#$id\" method=\"get\">";
                        echo "<input type=\"submit\" value=\"Cancel changes\">";
                    echo "</form>";
                    echo "</div>";
                }
                
                // Form to delete story -- only show if user posted it
                if ($_SESSION['user_id'] == $user_id) {
                    echo "<div class=\"inner_story\">";
                    echo "<form action=\"delete_story.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                        echo "<input type=\"submit\" value=\"Delete story\">";
                    echo "</form>";
                    echo "</div>";
                }
                
                // Like/unlike button -- only if user is logged in
                if (isset($_SESSION['user_id'])) {
                    // QUERY for if user has already liked story
                    $like_stmt = $mysqli->prepare("SELECT COUNT(*), id, like_true FROM likes WHERE user_id=? AND story_id=?");
                    if (!$like_stmt) {
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    // Bind the parameter
                    $like_stmt->bind_param('ii', $user_id, $story_id);
                    $user_id = $_SESSION['user_id'];
                    $story_id = $id;
                    $like_stmt->execute();
                    $like_stmt->store_result();
                    $like_stmt->bind_result($cnt, $like_id, $like_true);
                    $like_stmt->fetch();
                    $like_stmt->close();
                    
                    // cnt will either be zero or one -- change "liked" boolean to false if cnt is 0
                    $liked = true;
                    if ($cnt == 0) {
                        $liked = false;
                    }
                    
                    // Query for total number of likes across users
                    $total_likes_stmt = $mysqli->prepare("SELECT COUNT(*) from likes WHERE story_id=?");
                    if (!$total_likes_stmt) {
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $total_likes_stmt->bind_param('i', $story_id);
                    $story_id = $id;
                    $total_likes_stmt->execute();
                    $total_likes_stmt->store_result();
                    $total_likes_stmt->bind_result($total_count);
                    $total_likes_stmt->fetch();
                    $total_likes_stmt->close();
                    
                    // Display total number of likes
                    echo "<br>";
                    echo "<div class = \"box\">";                   
                    echo "<strong>Likes: " . $total_count."</strong>";                   
                   // echo "<br>";
                    echo "</div>";
                    
                    // Show like button if story is not liked
                    if (!$liked) {
                        echo "<div class=\"inner\">";
                        echo "<form action=\"like_story.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"story_id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                        echo "<input type=\"submit\" value=\"Like\">";
                        echo "</form>";
                        echo "</div>";
                        
                    }
                    // Show unlike button if story is liked
                    else {
                        echo "<div class=\"inner\">";
                        echo "<form action=\"unlike_story.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"story_id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"token\" value=\"$token\"/>";
                        echo "<input type=\"submit\" value=\"Unlike\">";
                        echo "</form>";
                        echo "</div>";
                    }
                   
                }
                
                
                // Button to show comments for story
                // Don't show if comments currently showing for that story
                if (!($_GET['id'] == $id && $_GET['show'] == "true")) {
                    //$action = "/~kstathis/news.php#$id"; // Self-submitting form
                    $action = "/~tinani/mod3-G/news.php#$id";
                    echo "<div class=\"inner\">"; 
                    echo "<form action=\"$action\" method=\"get\">";
                        echo "<input type=\"hidden\" name=\"id\" value=$id>";
                        echo "<input type=\"hidden\" name=\"show\" value=true>";
                        echo "<input type=\"submit\" value=\"Show comments\">";
                    echo "</form>";
                    echo "</div>";
                }

                // If "Show comments" button pressed, show comments
                if (isset($_GET['id'])) {
                    // QUERY for story's comments
                    $comment_stmt = $mysqli->prepare("select id, user_id, story_id, comment_text from comments where story_id=?");
                    if (!$comment_stmt) {
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $comment_stmt->bind_param('i', $story_id);
                    $story_id = $_GET['id'];
                    $comment_stmt->execute();
                    $comment_stmt->store_result();
                    $comment_stmt->bind_result($comment_id, $comment_user_id, $comment_story_id, $comment_text);
                    
                    // Display the comments for the story
                    if ($id == $story_id && $_GET['show'] != "false") {
                        // Button to hide comments
                        //$action = "/~kstathis/news.php"; //Self-submitting
                         $action = "/~tinani/mod3-G/news.php";
                        echo "<div class=\"inner_story\">"; 
                        echo "<form action=\"$action#$id\" method=\"get\">";
                            echo "<input type=\"hidden\" name=\"show\" value=false>";
                            echo "<input type=\"submit\" value=\"Hide comments\">";
                        echo "</form>";
                        echo "</div>";
                        echo "<br>";
                        
                        while($comment_stmt->fetch()) {
                            // Query for user who posted comment
                            $comment_user_stmt = $mysqli->prepare("SELECT username FROM users WHERE id=?");
                            if (!$comment_user_stmt) {
                                printf("Query Prep Failed: %s\n", $mysqli->error);
                                exit;
                            }
                            $comment_user_stmt->bind_param('i', $comment_user_id);
                            $comment_user_stmt->execute();
                            $comment_user_stmt->bind_result($comment_username);
                            $comment_user_stmt->fetch();
                            $comment_user_stmt->close();
                            
                            // Display user who posted and comment text
                            echo htmlspecialchars($comment_username) . ": ";
                            echo htmlspecialchars($comment_text);
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