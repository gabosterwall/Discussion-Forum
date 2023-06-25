<?php
    function DBConnect()
    {
        $db = new SQLite3("./db/database.db");

        if (!$db){
            die("Failed to connect to the database");
        }
        return $db;
    }
?>