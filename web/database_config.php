<?php
$host='127.0.0.1';
$dbUsername='hacklabos';
$dbPassword='rU8toorqFmjeVwLIgnW7';
$dbName='hacklabos';
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
