<?php

    include "include/functions/config.php";

    session_start();

    $errors = array();

    if(empty($_POST['comment'])){
        $errors['comment'] = "Field must be filled. ";
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }
    elseif(empty($_POST['Id'])){
        $errors['general'] = "Unknown error occurred. ";
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }
    else{
        if(!validateText($_POST['comment'])){
            $errors['comment'] = "Invalid comment. ";
        }
        if(!empty($errors)){
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        if(!storeComment($_POST['Id'], $_SESSION['userid'], $_POST['comment'])){
            $errors['general'] = "Comment submission process failed. ";
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode(array('success' => true));
        exit;
    }
?>