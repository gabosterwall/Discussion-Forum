<?php

include "include/functions/config.php";

session_start();

$errors = array();

if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['cpassword'])){
    $errors['general'] = "All fields must be filled. ";
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'errors' => $errors));
    exit;
}
else{

    if(existsInDB("Username", $_POST['username'])){
        $errors['username'] = "Username is taken. ";
    }
    if(!validateUsername($_POST['username'])){
        $errors['username'] = "Username length must be greater than 3. ";
    }
    if(!validateEmail($_POST['email'])){
        $errors['email'] = "Invalid email format. ";
    }
    if(!validatePassword($_POST['password'])){
        $errors['password'] = "Password length must be greater than 8, contain at least one uppercase, and one lowercase character. ";
    }
    if(!confirmPasswords($_POST['password'], $_POST['cpassword'])){
        $errors['cpassword'] = "Passwords do not match. ";
    }

    if(!empty($errors)){
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }

    // Hash password after validation and then send to storeUser to store in db
    if(!storeUser($_POST['username'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT))){
        $errors['general'] = "Registration process failed. ";
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }
    
    header('Content-Type: application/json');
    echo json_encode(array('success' => true));
    exit;
}
    
?>