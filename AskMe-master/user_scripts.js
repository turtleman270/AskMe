function loginAjax(event) {
    var userid = document.getElementById("userid").value; // Get the username from the form
    var password = document.getElementById("password").value; // Get the password from the form
 
    var dataString = "userid=" + encodeURIComponent(userid) + "&password=" + encodeURIComponent(password);
 
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "login.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        alert(event.target.responseText);
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object
        if (jsonData.success) {
            var userID = jsonData.id;
            var token = jsonData.token; // Set token for use in POST requests while logged in
            post_to_url("posts.php", {sessionToken: token, sessionUserID: userID});
        } else {
            alert("You were not logged in.  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

function newUserAjax(event) {
    
    var newpassword = document.getElementById("newpassword").value; // Get the password from the form
 
    var dataString = "newpassword=" + encodeURIComponent(newpassword);
 
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "newuser.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        alert(event.target.responseText);
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object
        if (jsonData.success) {
            alert("New user created! ID assigned : "  + jsonData.userid + "please log in.");
        } else {
            alert("New user not created.  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

// From http://developertipsandtricks.blogspot.com/2014/03/post-data-with-javascript-like-form.html
function post_to_url(path, params) {
    method = "post";

    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }
    document.body.appendChild(form);
    form.submit();
}