<?php

include "include/functions/config.php";

session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $userInfoArray = fetchUserInfo($_SESSION['userid']);

    $jsonData = array(
        'Image' => ($userInfoArray['Image'] == null || $userInfoArray['Image'] == '') ? null : $userInfoArray['Image'],
        'Username' => $userInfoArray['Username'],
        'Email' => $userInfoArray['Email']
    );

    header('Content-Type: application/json');
    echo json_encode($jsonData);
    exit;
}

?>