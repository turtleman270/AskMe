<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
        <script src="post_scripts.js"></script>
        <title>Ask Me</title>
    </head>
    <body>
        <div id="postdetail"></div>
        <script>
            <?php
                echo "var token = \"" . $_SESSION['token'] . "\";";
                echo "var userid = \"" . $_SESSION['userid'] . "\";";
                echo "var question_id = \"" . $_GET['id'] . "\";";
            ?>
            // Load assignments upon initial page load
            document.addEventListener("DOMContentLoaded", getPost, false);
        </script>
    </body>
</html>