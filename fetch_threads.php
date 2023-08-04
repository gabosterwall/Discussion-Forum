<?php

    include "include/functions/db_connect.php";

    $db = DBConnect();
    $sql = "SELECT Threads.*, Users.Username, Users.Image
            FROM Threads
            INNER JOIN Users ON Threads.UserID = Users.Id
            ORDER BY Threads.Id";

    $stmt = $db->prepare($sql);
    $result = $stmt->execute();

    $rows = array();

    while($row = $result->fetchArray(SQLITE3_ASSOC)){
        $rows[] = $row;
    }

    $db->close();

    echo json_encode($rows);
    exit;

?>