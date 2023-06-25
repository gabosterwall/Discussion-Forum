<?php

include "include/functions/config.php";

session_start();

$errors = array();

if(isset($_POST['username']) && isset($_POST['password'])){

    if(!validateUsername($_POST['username'])){
        $errors['username'] = "Username length must be greater than 3. ";
    }

    if(!validatePassword($_POST['password'])){
        $errors['password'] = "Password length must be greater than 8, contain at least one uppercase and one lowercase character. ";
    }
    
    if (!empty($errors)){
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }

    if(!authorizeUser($_POST['username'], $_POST['password'])){
        $errors['loginForm'] = "Login process failed. ";
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }

    $userInfo = fetchUserId($_POST['username']);

    $_SESSION['userid'] = $userInfo['Id'];

    header('Content-Type: application/json');
    echo json_encode(array('success' => true));
    exit;
}
else{
    $errors['loginForm'] = "All fields must be filled. ";
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'errors' => $errors));
    exit;
}

?>