<?php
    
    error_reporting(E_ALL || E_STRICT);

    include_once 'constants.php' ;

    $mysqli = new MySQLi(HOST, USER, PASSWORD, DATABASE);
    
    mysqli_query("set names utf8");
    
    if($mysqli->connect_error){
        echo 'Connection Failed!
                Error #' . $mysqli->connect_errno
                . ': ' . $mysqli->connect_error;
        exit(0);
    }
    
?>