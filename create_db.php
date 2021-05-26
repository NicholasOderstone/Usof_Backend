<?php
    $dsn = $dsn = "mysql:host=localhost";
    $pdo = new PDO($dsn,"root","");

    $pdo->query("CREATE DATABASE `usof_db`;");