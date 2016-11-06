// GLOBAL VARIABLES
// var addDialog;
// var editDialog;
// var uploadDialog;
// var selectedAssignmentID = 0;

// PLANNER FUNCTIONS

// Gets user's assignments from database

// Remove all rows from calendar table
function clearPosts() {
    while (document.getElementById("postbody").firstChild) {
        document.getElementById("postbody").removeChild(document.getElementById("postbody").firstChild);
    }
}

function getAllPosts() {

    clearPosts();

    var dataString = "token=" + encodeURIComponent(token);
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "getPosts.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object
        if (jsonData.success) {
            displayPosts(jsonData);
        } else {
            alert("Error fetching assignments.  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

function getFollowingPosts() {

    clearPosts();

    var dataString = "token=" + encodeURIComponent(token);
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "getFollowingPosts.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object
        if (jsonData.success) {
            displayPosts(jsonData);
        } else {
            alert("Error fetching assignments.  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}


// Add post to database
function addPost(event) { 
    // Get fields from form
    var title = $("#title").val();
    var question = $("#question").val();
    
    var dataString = "token=" + encodeURIComponent(token) + "&title=" + encodeURIComponent(title) + "&question=" + encodeURIComponent(question); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "addpost.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        alert(event.target.responseText);
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object

        if (jsonData.success) {
            alert("Question asked!");
            getPosts();
        } else {
            alert("Question not added.  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

// get post detail
function getPost() { 
    
    var dataString = "token=" + encodeURIComponent(token) + "&question_id=" + encodeURIComponent(question_id);
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "getPost.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        alert(event.target.responseText);
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object
        if (jsonData.success) {
            var title = jsonData.title;
            var question = jsonData.question;
            var dateString = jsonData.datestring;
            var date_array = dateString.split("-");
            var year = parseInt(date_array[0]);
            var month = parseInt(date_array[1]);
            var day = parseInt(date_array[2]);
            var jsDate = Date(year, month, day);
            
            var div = document.createElement("div");
            div.setAttribute("id", question_id);
            
            // Title
            var titleF = document.createElement("h4");
            titleF.setAttribute("class", title);
            titleF.appendChild(document.createTextNode(title));
            div.appendChild(titleF);

            // date
            var dateF = document.createElement("p");
            dateF.setAttribute("class", jsDate);
            dateF.appendChild(document.createTextNode(year + "-" + month + "-" + day));
            div.appendChild(dateF);

            // Question
            // var questionF = document.createElement("p");
            // questionF.setAttribute("class", question);
            // questionF.appendChild(document.createTextNode(question));
            // div.appendChild(questionF);

            div.appendChild(document.createElement("br"));

            var btn = document.createElement("BUTTON");
            btn.setAttribute("id", "follow_btn");
            btn.appendChild(document.createTextNode("follow"));
            div.appendChild(btn);

            var body = document.getElementById("postdetail");
            body.appendChild(div);

            document.getElementById("follow_btn").addEventListener("click", function() {
                followPost();
            });
            
        } else {
            alert("Can't get question detail!  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

// follow post
function followPost() { 
    // Get fields from form
    
    var dataString = "token=" + encodeURIComponent(token) + "&question_id=" + encodeURIComponent(question_id);
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "followPost.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object
        if (jsonData.success) {
            alert("Question followed!");
            getPosts();
        } else {
            alert("Question not followed.  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}




// Log out
function logoutAjax() {
    //window.location = "login_register.html";
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "logout.php", true); // Starting a POST request
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // Parse the JSON into a JavaScript object
        if (jsonData.success) { 
            alert("Logged out!");
            window.location = "login_register.html";
        } else {
            alert("Logging out failed.  " + jsonData.message);
        }
        this.removeEventListener("load", this);
    }, false); // Bind the callback to the load event
    xmlHttp.send(null); // Send the data
}

// UTILITIES
function removeNodesByParentID(nodeID) {
    while (document.getElementById(nodeID).firstChild) {
        var oldNode = document.getElementById(nodeID).firstChild;
        oldNode.parentNode.removeChild(oldNode);
    }
}

function displayPosts(jsonData) {

    for (i = 0; i < jsonData.count; i++) { // For each question in the jsonData response
                (function() {
                    // Parse jsonData
                    var question_id = jsonData[i].id;
                    var user_id = jsonData[i].user_id;
                    var title = jsonData[i].title;
                    var question = jsonData[i].question;
                    var dateString = jsonData[i].datestring;
                    var date_array = dateString.split("-");
                    var year = parseInt(date_array[0]);
                    var month = parseInt(date_array[1]);
                    var day = parseInt(date_array[2]);
                    var jsDate = Date(year, month, day);
                    
                    var div = document.createElement("div");
                    div.setAttribute("id", question_id);
                    
                    // Title
                    var titleF = document.createElement("h4");
                    titleF.setAttribute("id", title);
                    titleF.appendChild(document.createTextNode(title));
                    div.appendChild(titleF);
                    var t = document.getElementById(title);
                    titleF.setAttribute("href", "postDetail.php?id=" + question_id);

                    // date
                    var dateF = document.createElement("p");
                    dateF.setAttribute("class", jsDate);
                    dateF.appendChild(document.createTextNode(year + "-" + month + "-" + day));
                    div.appendChild(dateF);

                    // Question
                    var questionF = document.createElement("p");
                    questionF.setAttribute("class", question);
                    questionF.appendChild(document.createTextNode(question));
                    div.appendChild(questionF);

                    div.appendChild(document.createElement("br"));

                    var body = document.getElementById("postbody");
                    body.appendChild(div);
                }());                 
            }
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
