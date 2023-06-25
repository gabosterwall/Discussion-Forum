
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

// This and the next functions got messy real quick, but I prevailed :)



function loadComments(post_id){
    $.post("fetch_comments.php", { postid : post_id },
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

            $userProfile.append('<button class="reply-btn">Reply</button>');

            $commentContainer.append($userProfile);

            $commentContainer.append($comment);
            
            $result.prepend($commentContainer);
            });
        }
    )
}

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
        
            let $postBox = $('<div class="post-box"></div>');

            $postBox.append('<h2 id="thread-title">' + element['Title'] + '</h2>');

            let $userProfile = $('<div class="user-profile"></div>');
            
            if (element['Image'] == null || element['Image'] == '') {
                $userProfile.append('<img src="img/default_user.png">');
            }
            else{
                $userProfile.append('<img src="' + element['Image'] + '">');
            }

            $userProfile.append('<p>' + element['Username'] + " posted on " + element['CreatedDateTime'] + '</p>');

            $postBox.append($userProfile);

            $postBox.append('<div id="thread-desc">' + element['Subject'] + '</div>');

            $postBox.append('<button class="toggle-comments-btn">Comments</button>');
            
            // Comment section for each post: 

            let $commentSection = $('<div class="comment-section" id="' + element['Id'] + '"> <h2>Comments:</h2> </div>');

            let $commentDisplay = $('<div class="output">No comments published yet!</div>');

            $commentSection.append($commentDisplay);

            let $commentForm = $('<form class="comment-form" method="POST"></form>');

            $commentSection.append($commentForm);

            $commentForm.append('<div class="comment-error"></div>');
            $commentForm.append('<textarea class="input-field" name="comment" id="comment" placeholder="Add a comment..."></textarea>');
            $commentForm.append('<input type="submit" class="ajax-btn" id="comment-submit-btn" value="Submit"/>');
            
            $postBox.append($commentSection);
            
            $('.post-container').append($postBox);

            loadComments(element['Id']);

            $(".comment-section").hide();

            });
        }
    )
}

function loadThreads(){
    $.post("fetch_threads.php",
        function(response){
            $(".thread-container").empty();
            response = JSON.parse(response);

            if(response.length == 0){
                $('.thread-container').append('<p class="no-show">No threads available!</p>');
            }

            response.forEach(element => {

            let $threadBox = $('<div class="thread-box" id="' + element['Id'] + '"></div>');

            $threadBox.append('<h2 id="thread-title">' + element['Topic'] + '</h2>');
            
            let $userProfile = $('<div class="user-profile"></div>');

            if (element['Image'] == null || element['Image'] == '') {
                $userProfile.append('<img src="img/default_user.png">');
            } else {
                $userProfile.append('<img src="' + element['Image'] + '">');
            }

            $userProfile.append('<p>' + element['Username'] + " posted on " + element['CreatedDateTime'] + '</p>');

            $threadBox.append($userProfile);
            
            $(".thread-container").append($threadBox);

            });
        }
    )
}

function storeThread(){
    $("#submit-thread-btn").click(function (e) {
        e.preventDefault();
        
        // better client-side validation, but commented out because server-side does the same but better

        /*
        let topic = $('#topic').val().trim();
        if (topic === '') {
            alert('Error: Topic missing.');
            $('#topic').focus();
            return;
        }
        */

        $("#thread-error").empty();
        let formData = $("#thread-form").serialize();
        
        $.ajax({
            url: "store_thread.php",
            data: formData,
            type: 'POST',
            success: function (response)
            {
                if(response == true){
                    $("#topic").val("");
                    loadThreads();
                }
                else{
                    $("#thread-error").append(response);
                }
            }
        });
    });
}

function storePost(){
    $("#submit-post-btn").click(function (e) {
        e.preventDefault();
        $("#post-error").empty();

        // better client-side validation, but commented out because server-side does the same but better

        /*
        let title = $('#title').val().trim();
        let subject = $('#subject').val().trim();
        if (title === '') {
            alert('Error: Title missing.');
            $('#title').focus();
            return;
        }
        if (subject === '') {
            alert('Error: Subject missing.');
            $('#subject').focus();
            return;
        }
        */

        let formData = $("#post-form").serializeArray();

        let thread_id = $('.post-container').attr('id');

        formData.push({ name: 'Id', value: thread_id });

        $.ajax({
            url: "store_post.php",
            data: $.param(formData),
            type: 'POST',
            success: function (response)
            {
                if(response == true){
                    $("#title").val("");
                    $("#subject").val("");
                    loadPosts(thread_id);
                }
                else{
                    $("#post-error").append(response);
                }
            }
        });
    });
}

function storeComment(){
    $(document).on('click', '.ajax-btn', function(e) {
        e.preventDefault();
        let commentForm = $(this).closest('form');
        let formData = commentForm.serializeArray();
        let post_id = commentForm.closest('.comment-section').attr('id');
    
        let $commentSection = $('.comment-section[id="' + post_id + '"]');
        let $error = $commentSection.find(".comment-error");
        $error.empty();
        let $comment = $commentSection.find("#comment");

        formData.push({ name: 'postid', value: post_id });

        $.ajax({
            url: "store_comment.php",
            data: $.param(formData),
            type: 'POST',
            success: function (response)
            {
                if(response == true){
                    $comment.val("");
                    loadComments(post_id);
                }
                else{
                    $error.append(response);
                }
            }
        });
    });
}

/*function storeReply(){
    $(document).on('click', '.ajax-btn', function(e) {
        e.preventDefault();
        let replyForm = $(this).closest('form');
        let formData = replyForm.serializeArray();
        let comment_id = replyForm.closest('.comment-section').attr('id');
    
        let $replyForm = $('.reply-form[id="' + comment_id + '"]');
        let $error = $replyForm.find(".reply-error");
        $error.empty();
        let $reply = $replyForm.find("#reply");

        formData.push({ name: 'commentid', value: post_id });

        $.ajax({
            url: "store_comment.php",
            data: $.param(formData),
            type: 'POST',
            success: function (response)
            {
                if(response == true){
                    $comment.val("");
                    loadComments(post_id);
                }
                else{
                    $error.append(response);
                }
            }
        });
    });
}
*/

function logoutUser(){
    $(document).on('click', '#logout', function(){
        $.ajax({
            url: "logout.php",
            type: 'POST'
        });
    });
}

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
                        $('#content-container').empty();
                    }, 2000);
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
}

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
                        $('#content-container').load('include/view/_login.php');
                    }, 3000);
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
}

function handleUserInputErrors(errors){
    for (let field in errors) {
        let errorMessage = errors[field];
        let inputField = $(`[name="${field}"]`);
        
        inputField.addClass('error-highlight');
        $(".message-box").show();
        
        $('.message-box').append('<p class="error-message">' + errorMessage + '</p>');
    }
}

function handleLinkClick(e){
    e.preventDefault(); // Prevent default link behavior

    let page = $(this).attr('id');
    let url = "include/view/_" + page +".php";

    // Perform an AJAX request to fetch the content
    $.ajax({
        url: url,
        method: 'GET',
        success: function(data) {
        // Update the container with the fetched content
        $('#content-container').html(data);

        /* Update the URL using the History API
        history.pushState({}, '', page);
        */
        }
    });
}


$(document).ready(function(){
    
    // jQuery methods go here...

    $('.ajax-link').on('click', handleLinkClick);

    // Close button functionality
    $(document).on('click', '.close-button', function(){
        $('#content-container').empty();
        /*
        $('#content-container').fadeOut(100, function() {
        $(this).empty(); // Clear the content after fading out
        $(this).show(); // Show the container again
        });
        */
    });

    storeUser();

    loginUser();

    logoutUser();

    /*storeThread();
    
    loadThreads();

    storePost();

    storeComment();

    $(document).on('click', '#toggle-form', function() {
        $(".hidden-form").toggle();
    });

    $('.thread-container').on('click', '.thread-box', function() {
        let thread_id = $(this).attr('id');
        $('#main-threads').hide();
        $('#main-posts').show();
        loadPosts(thread_id);
    });

    $(document).on('click', '#unload-btn', function() {
        $("#main-posts").hide();
        $('#main-threads').show();
    });

    $(document).on('click', '.toggle-comments-btn', function() {
        
        $(this).closest('.post-box').find('.comment-section').toggle();
       
    });

    */

});