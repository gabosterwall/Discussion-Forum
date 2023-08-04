<?php

    require_once "include/functions/config.php";

    if(empty($_POST['Id'])){
        $errors['general'] = "Unknown error occurred. ";
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'errors' => $errors));
        exit;
    }
    else{
        $db = DBConnect();

        $sql = "SELECT Comments.*, Users.Username, Users.Image
                FROM Comments
                INNER JOIN Users ON Comments.AuthorID = Users.Id
                WHERE Comments.PostID = :Id
                ORDER BY Comments.Id ASC";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Id', $_POST['Id'], SQLITE3_TEXT);
        $result = $stmt->execute();
        
        $rows = array();
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $rows[] = $row;
        }

        $db->close();

        echo json_encode($rows);
        exit;
    }
?>