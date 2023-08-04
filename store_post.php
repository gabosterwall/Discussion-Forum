<?php

    include "include/functions/config.php";

    session_start();

    $errors = array();

    if(empty($_POST['title']) || empty($_POST['description'])){
        $errors['general'] = "Field must be filled. ";
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
        if(!validateText($_POST['title'])){
            $errors['topic'] = "Invalid title. ";
        }
        if(!validateText($_POST['description'])){
            $errors['description'] = "Invalid description. ";
        }
        if(!empty($errors)){
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        if(!storePost($_POST['Id'], $_SESSION['userid'], $_POST['title'], $_POST['description'])){
            $errors['general'] = "Post submission process failed. ";
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode(array('success' => true));
        exit;
    }
?>