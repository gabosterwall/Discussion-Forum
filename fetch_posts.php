<?php

    include "include/functions/config.php";    

    $errors = array();

    if(empty($_POST['Id'])){
        $errors['general'] = "Error: Thread not available. ";
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }
    else{
        $db = DBConnect();
        $sql = "SELECT Posts.*, Users.Username, Users.Image
                FROM Posts
                INNER JOIN Users ON Posts.AuthorID = Users.Id
                WHERE Posts.ThreadID = :Id
                ORDER BY Posts.Id";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Id', $_POST['Id'], SQLITE3_TEXT);
        $result = $stmt->execute();
        
        // Here each row in the database gets stored as an array in another array to easily use it for display
        $rows = array();
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $rows[] = $row;
        }

        $db->close();

        echo json_encode($rows);
        exit;
    } 
?>