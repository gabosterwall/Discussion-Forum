<?php

include "include/functions/config.php";

session_start();

$oldInfo = fetchUserInfo($_SESSION['userid']);

$errors = array();

if(empty($_POST['password']) || empty($_POST['cpassword'])){
    $errors['password'] = "These fields are required to update any information.";
    $errors['cpassword'] = "";
}else{
    if(!verifyAndConfirmPasswords($_POST['password'], $_POST['cpassword'], $oldInfo['Password'])){
        $errors['password'] = "Password verification failed.";
    }

    if(isset($_POST['new_username']) && !empty($_POST['new_username'])){

        if(!validateUsername($_POST['new_username'])){
            $errors['new_username'] = "Invalid username format. ";
        }else{

            $newValue = $_POST['new_username'];
            $valueType = 'Username';

            if(existsInDB($valueType, $newValue)){
                $errors['new_username'] = "Username is already taken. ";
            }
        }
    }

    if(isset($_POST['new_email']) && !empty($_POST['new_email'])){

        if(!validateEmail($_POST['new_email'])){
            $errors['new_email'] = "Invalid email format. ";
        }else{

            $newValue = $_POST['new_email'];
            $valueType = 'Email';

            if(existsInDB($valueType, $newValue)){
                $errors['new_email'] = "Email is already taken. ";
            }
        }
    }

    if(isset($_POST['new_password']) && !empty($_POST['new_password'])){

        if($_POST['password'] === $_POST['new_password']){
            $errors['new_password'] = "New password should be different from the current password. ";
        }elseif(!validatePassword($_POST['new_password'])){
            $errors['new_password'] = "Invalid password format. ";
        }
    }

    if(isset($_FILES['new_img']) && $_FILES['new_img']['name'] != ''){

        $new_image = $_FILES['new_img'];

        if($new_image["size"] > 2000000){
            $errors['new_img'] = "Image size exceeds the limit. ";
        }
    }
}

// Error checking before any individual updates
if (!empty($errors)) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'errors' => $errors));
    exit;
}

// Individual updates, user experience + 10 points
if(isset($_POST['new_username']) && !empty($_POST['new_username'])){

    $newValue = $_POST['new_username'];
    $valueType = 'Username';

    if(!updateDatabase_User($valueType, $newValue, $_SESSION['userid'])){
        $errors['new_username'] = "Failed to update username. ";
    }
}

if(isset($_POST['new_email']) && !empty($_POST['new_email'])){

    $newValue = $_POST['new_email'];
    $valueType = 'Email';

    if(!updateDatabase_User($valueType, $newValue, $_SESSION['userid'])){
        $errors['new_email'] = "Failed to update email. ";
    }
}

if(isset($_POST['new_password']) && $_POST['new_password'] != ''){

    $newValue = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $valueType = 'Password';

    if(!updateDatabase_User($valueType, $newValue, $_SESSION['userid'])){
        $errors['new_password'] = "Failed to update password. ";
    }
}

if(isset($_FILES['new_img']) && $_FILES['new_img']['name'] != ''){

    $new_image = $_FILES['new_img'];

    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["new_img"]["name"]);

    if($target_file != 'img/'){
        if (move_uploaded_file($_FILES["new_img"]["tmp_name"], $target_file)){

            $newValue = $target_file;
            $valueType = 'Image';

            if(!updateDatabase_User($valueType, $newValue, $_SESSION['userid'])){
                $errors['new_img'] = "Failed to update image. ";
            }
        }else{
            $errors['new_img'] = "Failed to upload image. ";
        }
    }
}

// Error checking after updates
if (!empty($errors)) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'errors' => $errors));
    exit;
}

// No errors: 
header('Content-Type: application/json');
echo json_encode(array('success' => true));
exit;
