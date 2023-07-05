
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

// Function to dynamically load the _profile html and then fill it with the users info
function loadUserInfo(url){
    $.ajax({
        url: "profile.php",
        type: 'POST',
        success: function(data){
            $('.main-page').load(url, function (){
                $('.profile img').attr('src', data.Image || 'img/default.png');
                $('.profile-box h2[name="Username"]').text(data.Username);
                $('.profile-box h2[name="Email"]').text(data.Email);
            });
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
                        location.reload();
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
                        $('#user-popup').load('include/view/_login.php');
                    }, 2000);
                }
                else{
                    handleUserInputErrors(response.errors);
                }
            }
        });
    });
}

// Function to handle error messages related to user input on popup form
function handleUserInputErrors(errors){
    for (let field in errors) {
        let errorMessage = errors[field];
        let inputField = $(`[name="${field}"]`);
        
        inputField.addClass('error-highlight');
        $(".message-box").show();
        
        $('.message-box').append('<p class="error-message">' + errorMessage + '</p>');
    }
}

// Function to handle links on the navbar
function handleLinkClick(e){
    e.preventDefault(); // Prevent default link behavior

    let page = $(this).attr('id');
    let url = "include/view/_" + page +".php";

    switch(page){
        case "login":
            $('#user-popup').load(url);
            break;
        case "register": 
            $('#user-popup').load(url);
            break;
        case "profile":
            loadUserInfo(url);
            break;
        default:
            break;
    }

    //$('#content-container').load(url);

    // Perform an AJAX request to fetch the content
    /*$.ajax({
        url: url,
        method: 'POST',
        success: function(data) {
        // Update the container with the fetched content
        $('#user-popup').html(data);

        
        }
    });
    */
    
}


$(document).ready(function(){
    
    // jQuery methods go here...

    $('.ajax-link').on('click', handleLinkClick);

    // Close button functionality
    $(document).on('click', '.close-button', function(){
        $('#user-popup').empty();
    });

    storeUser();

    loginUser();

    logoutUser();


});