<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kgtrip";

// Create DSN (Data Source Name) for PDO
$dsn = 'mysql:host='.$servername.';dbname='.$dbname.';charset=utf8';

try {
    // ADMIN CONNECTION (PDO)
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

// SITE CONNECTION (MySQLi)
$conn = new mysqli($servername, $username, $password, $dbname);

// Check MySQLi CONNECTION
if ($conn->connect_error) {
    die("MySQLi Connection failed: " . $conn->connect_error);
}
?>
