<?php
    $db_host = "127.0.0.1";
    $db_user = "root";
    $db_password = "123";
    $db_name = "tripec";

    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name); 
    $createSql = "CREATE TABLE `test` (`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,`name` varchar(16) NOT NULL)";
    $insertSql = "INSERT INTO `test` (name) VALUES ('test')"; 

    $ret = $mysqli->query($createSql);
    $ret = $mysqli->query($insertSql);

    $mysqli->close(); 