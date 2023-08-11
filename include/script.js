
function validate(formName){

    let form = document.forms[formName];

    let user = form['username'].value;
    let email = form['email'].value;
    let pwd = form['password'].value;
    let cpwd = form['cpassword'].value;

    if(user.length > 3 && validateEmail(email) && passwordConfirm(pwd, cpwd) && passwordChecker(pwd)){
        return true;
    }
    let error= "";
    if(user.length < 4)
        error += "Username must be at least 4 letters long\r\n";
    if(!validateEmail(email))
        error += "Please enter a valid email address\r\n";
    if(!passwordConfirm(pwd, cpwd))
        error += "Passwords do not match";
    if(!passwordChecker(pwd))
        error += "Password must be longer than 8 characters and contain at least one numeric value, one lowercase letter and one uppercase letter";
    alert(error)
    return false;
}

function validateEmail(email){
    if(email.lastIndexOf(".") > email.indexOf("@") + 2 && email.indexOf("@") > 0 && email.length - email.lastIndexOf(".") >2 ){
        return true;
    }
        return false;
}
function passwordChecker(pwd){
    if(pwd.length > 7 && hasNumeric(pwd) && hasUppercase(pwd) && hasLowercase(pwd))
        return true;
    return false;
}
function passwordConfirm(pwd, cpwd){
    if(pwd === cpwd)
        return true;
    return false;
}
function hasNumeric(s){
    for (let i = 0; i < s.length; i++) {
        const c = s[i];
        if(!isNaN(c))
        return true;
    }
    return false;
}
function hasUppercase(s){
    for (let i = 0; i < s.length; i++) {
        const c = s[i];
        if(isNaN(c) && c == c.toUpperCase())
        return true;
    }
    return false;
}
function hasLowercase(s){
    for (let i = 0; i < s.length; i++) {
        const c = s[i];
        if(isNaN(c) && c == c.toLowerCase())
        return true;
    }
    return false;
}

// Ajax and jquery from now on

function updateUserInfo(){
    $(document).on('click', '#update-button', function(e){
        e.preventDefault();
        
        $(".message-box").empty();
        $(".error-highlight").removeClass("error-highlight");

        //let formData = $("#profForm").serialize();

        let formData = new FormData($("#profForm")[0]);

        $.ajax({
            url: "updateUserInfo.php",
            data: formData,
            type: 'POST',
            processData: false,
            contentType: false,
            success: function(response){
                if(response.success){
                    $(".message-box").show();
                    $(".message-box").append("Information successfully updated!");
                    $('.message-box').fadeIn().delay(1000).fadeOut(200);
                    $('#password').val('');
                    $('#cpassword').val('');
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
}

// Function to dynamically load the _profile html and then fill it with the users info
function loadProfileInfo(){
    $.ajax({
        url: "fetchUserInfo.php",
        type: 'POST',
        success: function(data){
            $('.profile img').attr('src', data.Image || 'img/default.png');
            $('.profile-box input[name="new_username"]').attr("placeholder", data.Username);
            $('.profile-box input[name="new_email"]').attr("placeholder", data.Email);
        }
    });
}

// Function to handle user logging out
function logoutUser(){
    $(document).on('click', '#logout', function(){
        $.ajax({
            url: "logout.php",
            type: 'POST',
            success: function(){
                location.reload();
            }
        });
    });
}

// Function to handle login of user
function loginUser(){
    $(document).on('click', '#login-button', function(e){
        e.preventDefault();
        
        $(".message-box").empty();
        $(".error-highlight").removeClass("error-highlight");

        let formData = $("#loginForm").serialize();

        $.ajax({
            url: "login.php",
            data: formData,
            type: 'POST',
            success: function(response){
                if(response.success){
                    $(".message-box").show();
                    $(".message-box").append("Login successful!");

                    setTimeout(function(){
                        location.reload();  // In order to "update" the navbar page had to reload because it relies on a session-variable
                    }, 2000);
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
}

// Function to dynamically store the user input on registration when data is valid
function storeUser(){
    $(document).on('click', '#register-button', function(e){
        e.preventDefault();
        
        $(".message-box").empty();
        $(".error-highlight").removeClass("error-highlight");

        let formData = $("#regForm").serialize();

        $.ajax({
            url: "register.php",
            data: formData,
            type: 'POST',
            success: function(response){

                if(response.success){
                    $(".message-box").show();
                    $(".message-box").append("Registration successful!");

                    setTimeout(function(){
                        $('#user-popup').load('include/models/_login.php'); //When successfull it loads the login popup
                    }, 2000);
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
}

// Function to handle error messages related to user input on input forms
function handleUserInputErrors(errors){
    for (let field in errors) {
        let errorMessage = errors[field];
        let inputField = $(`[name="${field}"]`);
        
        inputField.addClass('error-highlight');
        $(".message-box").show();
        
        //$('.message-box').append('<p class="error-message">' + errorMessage + '</p>');
        $(".message-box").append('<p class="error-message">' + errorMessage + '</p>').fadeIn().delay(1000).fadeOut(200);
    }
}



function loadComments(post_id){
    $.post("fetch_comments.php", { Id : post_id },
        function(response){

            let $commentSection = $('.comment-section[id="' + post_id + '"]');
            let $result = $commentSection.find('.output');

            $result.empty();

            response = JSON.parse(response);

            if(response.length == 0){
                $result.append('<p class="no-show">Be the first to comment!</p>');
            }

            response.forEach(element => {

            let $commentContainer = $('<div class="comment-container"></div>');

            let $comment = $('<h2 class="comment">' + element['Comment'] + '</h2>');

            let $userProfile = $('<div class="comment-sender"></div>');
            
            if (element['Image'] == null || element['Image'] == '') {
                $userProfile.append('<img src="img/default_user.png">');
            }
            else{
                $userProfile.append('<img src="' + element['Image'] + '">');
            }

            $userProfile.append('<p>' + element['Username'] + " : " + element['CreatedDateTime'] + '</p>');

            $commentContainer.append($userProfile);

            $commentContainer.append($comment);
            
            $result.prepend($commentContainer);
            });
        }
    )
}

function storeComment() {
    $(document).on('click', '#comment-submit-button', function(e){
        e.preventDefault();

        $(".message-box").empty();
        $(".error-highlight").removeClass("error-highlight");

        let $commentForm = $(this).closest('.comment-form');
        let formData = $commentForm.serializeArray();
        let post_id = $commentForm.closest('.comment-section').attr('id');
        formData.push({ name: 'Id', value: post_id });

        $.ajax({
        url: "store_comment.php",
        data: $.param(formData),
        type: 'POST',
        success: function(response){
            
            if (response.success) {
                $(".message-box").text("Comment successfully published!").fadeIn().delay(1000).fadeOut(200, function(){
                    loadComments(post_id);
                });
            }else{
                handleUserInputErrors(response.errors);
            }
        }
        });
    });
}

// Function to load posts with information from database
function loadPosts(thread_id){
    $.post("fetch_posts.php", { Id : thread_id },
        function(response){
            $('.post-container').empty();
            $('.post-container').attr('id', thread_id);
            response = JSON.parse(response);

            if(response.length == 0){
                $('.post-container').append('<p class="no-show">No posts available!</p>');
            }

            response.forEach(element => {
        
            // Individual posts gets created 
            let $postBox = $('<div class="post-box"></div>');
            $postBox.append('<h2>' + element['Title'] + '</h2>');
            $postBox.append('<h1 >' + element['Description'] + '</h1>');
            let $userProfile = $('<div class="user-profile"></div>');
            if (element['Image'] == null || element['Image'] == '') {
                $userProfile.append('<img src="img/default_user.png">');
            }
            else{
                $userProfile.append('<img src="' + element['Image'] + '">');
            }
            $userProfile.append('<p>' + element['Username'] + " posted on " + element['CreatedDateTime'] + '</p>');
            $postBox.append($userProfile);
            
            let $toggleComments = $('<button class="toggle-comments-btn">Comments</button>');
            $postBox.append($toggleComments);
            //$postBox.append('<button class="toggle-comments-button">Comments</button>');
            
            // Individual comment sections gets created for each post: 
            let $commentSection = $('<div class="comment-section" id="' + element['Id'] + '"> <h2>Comments:</h2> </div>');
            let $commentDisplay = $('<div class="output">No comments published yet!</div>');
            $commentSection.append($commentDisplay);
            let $commentForm = $('<form class="comment-form" method="POST"></form>');
            $commentSection.append($commentForm);
            $commentSection.append($commentForm);
            $commentForm.append('<textarea class="input-field" name="comment" id="comment" placeholder="Add a comment..."></textarea>');
            $commentForm.append('<input type="submit" class="ajax-btn" id="comment-submit-button" value="Submit"/>');
            $postBox.append($commentSection);
            $('.post-container').append($postBox);
            
            $commentSection.hide();
            $toggleComments.on('click', function(){
                $commentSection.toggle();
            })
            
            $(document).ready(function(){
                loadComments(element['Id']);
            })

            });
        }
    )
}

// Function to store posts in database
function storePost(){
    $(document).on('click', "#submit-post-button", function(e){
        e.preventDefault();

        $(".message-box").empty();
        $(".error-highlight").removeClass("error-highlight");

        let formData = $("#postForm").serializeArray();
        let thread_id = $('.post-container').attr('id');

        formData.push({ name: 'Id', value: thread_id });
        $.ajax({
            url: "store_post.php",
            data: $.param(formData),
            type: 'POST',
            success: function (response)
            {
                if(response.success){
                    setTimeout(function(){
                        $('#title').val('');
                        $('#description').val('');
                        $(".message-box").show();
                        $(".message-box").append("Post submission successful!");
                    }, 2000);
                    loadPosts(thread_id);
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
}

function handlePosts() {
    $(document).on('click','.thread-box', function(){
        let thread_id = $(this).attr('id');
        $('.main-page').load('include/models/_posts.php', function(){
            loadPosts(thread_id);
            let form = $(this).find('#postForm').hide();
            let toggleForm = $(this).find('#toggle-posts');
            form.hide();
            toggleForm.on('click', function() {
                form.toggle();
            });
        });
    });
}

// Function to ajax load threads from database
function loadThreads(){
    $.post("fetch_threads.php",
        function(response){
            $(".thread-container").empty();

            response = JSON.parse(response);

            if(response.length === 0){
                $(".thread-container").append('No threads available!');
            }

            response.forEach(element => {
                let $threadBox = $('<div class="thread-box" id="' + element['Id'] + '"></div>');
                $threadBox.append('<h2 id="thread-title">' + element['Topic'] + '</h2>');
                let $userProfile = $('<div class="user-profile"></div>');
                $userProfile.append('<p> Created by: ' + element['Username'] + '</p>');
                $threadBox.append($userProfile);
                $(".thread-container").append($threadBox);
            });
        }
    )
}

// function to ajax store new threads in database + load them on page
function storeThread(){
    $(document).on('click','#submit-thread-button', function(e){
        e.preventDefault();

        $(".message-box").empty();
        $(".error-highlight").removeClass("error-highlight");

        let formData = $("#threadForm").serialize();
        
        $.ajax({
            url: "store_thread.php",
            data: formData,
            type: 'POST',
            success: function (response)
            {
                if(response.success){
                    $(".message-box").text("Thread submission successful!").fadeIn().delay(1000).fadeOut(200, function() {
                        loadThreads();
                    });
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
    
}

function handleThreads(url) {
    $('.main-page').load(url, function() {
        loadThreads();
        let form = $('.threads').find('#threadForm');
        let toggleForm = $('.threads').find('#toggle-threads');
        form.hide();
        toggleForm.on('click', function() {
            form.toggle();
        });
    });
}

// Function to handle links on the navbar; switched to switch statements to handle each link separately
function handleLinkClick(e){
    e.preventDefault(); // Prevent default link behavior

    let page = $(this).attr('id');
    let url = "include/models/_" + page +".php";

    switch(page){
        case "login":
            $('#user-popup').load(url);
            break;
        case "register": 
            $('#user-popup').load(url);
            break;
        case "profile":
            $('.main-page').load(url,function(){
                loadProfileInfo();
                updateUserInfo();
            });
            break;
        case "threads":
            handleThreads(url);
        break;
        default:
            break;
    }
}

$(document).ready(function(){
    
    // jQuery methods go here...

    // Navbar link functionality
    $('.ajax-link').on('click', handleLinkClick);

    // Close button functionality
    $(document).on('click', '.close-button', function(){
        $('#user-popup').empty();
    });

    // Exit post section functionality
    $(document).on('click', '#exit-posts', function(){
        $('.main-page').load('include/models/_threads.php', function() {
            loadThreads();
            let form = $('.threads').find('#threadForm');
            let toggleForm = $('.threads').find('#toggle-threads');
            form.hide();
            toggleForm.on('click', function() {
                form.toggle();
            });
        });
    })

    storeUser();

    loginUser();

    logoutUser();

    storeThread();

    storePost();

    storeComment();
    
    handlePosts();
});