<?php
    ob_start(); //Turns on output buffering 
    session_start();
    date_default_timezone_set("Asia/Manila");
    
    try {
        // $con = new PDO("mysql:dbname=videotube;host=localhost", "root", "");
        // $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $con = new PDO('mysql:host=localhost;port=3307;dbname=videotube', 'root', '');
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    }
    catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>