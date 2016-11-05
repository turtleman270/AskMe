<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
        <title>Log in</title>
    </head>
    <body>
        <div id="users">
        <form action="login.php" method="POST">     
            <label>User name: <input type="text" name="userid" /></label>
            <label>Password: <input type="password" name="password" /></label>
            <input type="submit" value="Sign in"/>
        </form>
        <form action="newuser.php" method="POST">
            <label>New user name: <input type="text" name="userid"/></label>
            <label>New user password: <input type="password" name="password"/></label>
            <input type="submit" value="New user"/>
        </form>
        <form action="posts.php" method="POST">     
            <input type="submit" value="Guest"/>
        </form>
        <?php
            if ($_GET['login'] == "failed") {
                echo "<br>";
                echo "<strong>Login failed</strong>";
            }
        ?>
        </div>
    </body>
</html>
    
