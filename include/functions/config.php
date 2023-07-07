<?php

require_once "include/functions/db_connect.php";

// Validation functions
function validateUsername($username){
    if(strlen(trim($username)) > 3)
        return true;
    return false;
}

function validateText($text){
    if(strlen(trim($text)) > 0)
        return true;
    return false;
}

function validatePassword($password){
    $pattern="/(?=.*\d)(?=.*[a-zåäö])(?=.*[A-ZÅÄÖ]).{8,}/";
    return preg_match($pattern, $password);
}

function validateEmail($email){
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function confirmPasswords($password, $cpassword){
    return $password === $cpassword;
}

function verifyAndConfirmPasswords($password, $cpassword, $oldpassword){
    if (password_verify($password, $oldpassword) && $password === $cpassword){
        return true;
    }
    return false;
}


// Query functions
function storeUser($username, $email, $hash){

    $db = DBConnect();
 
    $sql = "INSERT INTO 'Users' ('Username', 'Email', 'Password') VALUES (:username, :email, :pwd) ";
 
    $stmt = $db->prepare($sql);
    $stmt -> bindParam(':username', $username, SQLITE3_TEXT); 
    $stmt -> bindParam(':email', $email, SQLITE3_TEXT);
    $stmt -> bindParam(':pwd', $hash, SQLITE3_TEXT);
 
    if($stmt->execute()){
       $db->close();
       return true;
    }
    else{
       $db->close();
       return false;
    }
}

function authorizeUser($username, $pwd){

    $db = DBConnect();

    $sql = "SELECT Password FROM 'Users' WHERE Username = :username";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, SQLITE3_TEXT);

    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $db->close();

    if($row === false){
        return false; 
    }

    return password_verify($pwd, $row['Password']);
}

function existsInDB($type, $value){

    $db = DBConnect();

    if (empty($type)) {
        $db->close();
        return false;
    }

    $sql = "SELECT EXISTS(SELECT 1 FROM Users WHERE :type = :value) AS exists_value";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':value', $value, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);

    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();

    return $row['exists_value'] == 1;
}

function updateDatabase_User($type, $newvalue, $userid){

    $db = DBConnect();

    if(empty($type)){
        $db->close();
        return false; 
    }

    $validTypes = ['Username', 'Email', 'Password', 'Image'];

    if (!in_array($type, $validTypes)) {
        return false; 
    }

    $sql = "UPDATE 'Users' SET $type = :newvalue WHERE Id = :userid";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':newvalue', $newvalue, SQLITE3_TEXT);
    $stmt->bindParam(':userid', $userid, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function fetchUserId($username){

    $db = DBConnect();

    $sql = "SELECT * FROM 'Users' WHERE Username = :username";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, SQLITE3_TEXT);

    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $db->close();

    return $row;
}

function fetchUserInfo($userid){

    $db = DBConnect();

    $sql = "SELECT * FROM 'Users' WHERE Id = :id";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $userid, SQLITE3_TEXT);

    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $db->close();

    return $row;
}



?>