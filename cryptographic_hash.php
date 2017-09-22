<?php
//a script for demonstrating how user input can be processed into a cryptographic hash and stored into a database
//receive input from $_POST superglobal 
$user_input = (string) $_POST['user_input'];

//connection parameters initialized with generic MAMP values
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cryptographic_hashes";

//mysqli class represents a connection between PHP and a MySQL database.
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if there is a string description of error via connection object property
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);} 

//int random_int ( int $min , int $max ) 
//Generates cryptographically secure pseudo-random integers
//If an appropriate source of randomness cannot be found, an Exception will be thrown.
$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$salt = '';
for ($i = 0; $i < 22; $i++) {
      $salt .= $characters[random_int(0, 61)];
 }
$salt = '$2a$09$' . $salt;


//hash string
//crypt(string $str [, string $salt ]) will return a hashed string using the standard Unix DES-based algorithm or alternative algorithms that may be available on the system.
//The salt parameter is optional. However, crypt() creates a weak hash without the salt.
//CRYPT_BLOWFISH - Blowfish hashing with a salt as follows: "$2a$", "$2x$" or "$2y$", a two digit cost parameter, "$", and 22 characters from the alphabet "./0-9A-Za-z". 

if (CRYPT_BLOWFISH == 1) {
    $hash = crypt($user_input, $salt);
}
else {
    echo "Blowfish DES not supported. ";
}

//store SQL query into a variable
$sql = "INSERT INTO Hashes (hash_value, salt)
VALUES ('$hash','$salt');

//mysqli::query performs a query on the database previously connected to
//Returns FALSE on failure. For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE.
$result = $conn->query($sql);

if($result){echo $hash;}
?>