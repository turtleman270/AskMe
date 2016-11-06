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
        <div id="sidebar">
            <form action="addpost.php" method="POST">
                <strong><label>Ask a Question</label></strong><hr>
                <label>Title: <input type="text" size = 32 name="title"/></label><br>
                <label>Question: </label><br><textarea cols="30" rows="9" name="question"></textarea><br>
                <input type="hidden" name="token"/>
                <input type="submit" value="Ask Question"/>
            </form>
        </div>
        <!-- <div id="button3">
            <button id="add_question_btn">Ask Question</button>
        </div> -->
        <div id="postbody"></div>
        <script>
            // $("#add_question_btn").button().on("click", function() {
            //     addPost();
            // });
            <?php
                echo "var token = \"" . $_SESSION['token'] . "\";";
                echo "var userid = \"" . $_SESSION['userid'] . "\";";
            ?>
            // Load assignments upon initial page load
            // FIXME: getFollowingPosts
            //document.addEventListener("DOMContentLoaded", getFollowingPosts, false);
            document.addEventListener("DOMContentLoaded", getAllPosts, false);
        </script>
    </body>
</html>