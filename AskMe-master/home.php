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
            <label>Age: <input type="text" name="age"/></label>
            <label>Weight: <input type="text" name="weight"/></label>
            <label>Height: <input type="text" name="height"/></label>
            <label>Gender: <input type="text" name="gender"/></label>
            <label>Allergies: <input type="text" name="allergies"/></label>
            <label>Password: <input type="password" name="password"/></label>
            <label>Confirm Password: <input type="password" name="confirmpassword"/></label>
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
    
