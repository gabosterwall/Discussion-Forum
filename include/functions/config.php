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

    return password_verify($pwd, $row['Password']);
}


function existsInDatabase($type, $value) {
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

function updateDatabase_User($type, $newvalue, $userid) {
    $db = new SQLite3("./db/database.db");
    if(!$db) {
       die("Failed to connect to database");
    }

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

function fetchUserInfo(){

    $db = DBConnect();

    $sql = "SELECT * FROM 'Users' WHERE Id = :id";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $_SESSION['userid'], SQLITE3_TEXT);

    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $db->close();

    return $row;
}

function storeThread($topic, $userid){

    $db = DBConnect();

    $sql = "INSERT INTO 'Threads' ('UserID', 'Topic') VALUES (:userid, :topic) ";

    $stmt = $db->prepare($sql);
    $stmt -> bindParam(':userid', $userid, SQLITE3_TEXT); 
    $stmt -> bindParam(':topic', $topic, SQLITE3_TEXT);

    if($stmt->execute()){
        $db->close();
        return true;
    }
    else{
        $db->close();
        return false;
    }
}

function storePost($threadid, $userid, $title, $subject){

    $db = DBConnect();
 
    $sql = "INSERT INTO 'Posts' ('ThreadID', 'AuthorID', 'Title', 'Subject') VALUES (:threadid, :userid, :title, :subject) ";
 
    $stmt = $db->prepare($sql);
    $stmt -> bindParam(':threadid', $threadid, SQLITE3_TEXT);
    $stmt -> bindParam(':userid', $userid, SQLITE3_TEXT); 
    $stmt -> bindParam(':title', $title, SQLITE3_TEXT);
    $stmt -> bindParam(':subject', $subject, SQLITE3_TEXT);
 
    if($stmt->execute()){
       $db->close();
       return true;
    }
    else{
       $db->close();
       return false;
    }
}

function storeComment($postid, $userid, $comment){

    $db = DBConnect();
 
    $sql = "INSERT INTO 'Comments' ('PostID', 'AuthorID', 'Comment') VALUES (:postid, :authorid, :comment) ";
 
    $stmt = $db->prepare($sql);
    $stmt -> bindParam(':postid', $postid, SQLITE3_TEXT); 
    $stmt -> bindParam(':authorid', $userid, SQLITE3_TEXT);
    $stmt -> bindParam(':comment', $comment, SQLITE3_TEXT);
 
    if($stmt->execute()){
       $db->close();
       return true;
    }
    else{
       $db->close();
       return false;
    }
}

?>