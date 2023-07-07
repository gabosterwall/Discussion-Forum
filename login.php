<?php

include "include/functions/config.php";

session_start();

$errors = array();

$type = "Username";

if (empty($_POST['username']) || empty($_POST['password'])){

    $errors['general'] = "All fields must be filled. ";
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'errors' => $errors));
    exit;
}
else{
    
    if(!validateUsername($_POST['username'])){
        $errors['username'] = "Username length must be greater than 3. ";
    }

    if(!validatePassword($_POST['password'])){
        $errors['password'] = "Password length must be greater than 8, contain at least one uppercase and one lowercase character. ";
    }
    
    if(!empty($errors)){
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }

    if(!authorizeUser($_POST['username'], $_POST['password'])){
        $errors['wrongpwd'] = "Wrong username and/or password. ";
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

?>