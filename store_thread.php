<?php
    include "include/functions/config.php"; 

    session_start();

    $errors = array();

    if(empty($_POST['topic'])){
        $errors['general'] = "Field must be filled. ";
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }
    else{
        if(!validateText($_POST['topic'])){
            $errors['topic'] = "Field must be filled. ";
        }
        if(!empty($errors)){
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        if(!storeThread($_POST['topic'], $_SESSION['userid'])){
            $errors['general'] = "Thread submission process failed. ";
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode(array('success' => true));
        exit;
    }
?>