<?php
// Šis kods izveido savienojumu ar MySQL datubāzi, izmantojot PDO. 
// Ja savienojuma izveide neizdodas, tiek izvadīts kļūdas paziņojums.

$db_name = 'mysql:host=localhost;dbname=shop_db';
$user_name = 'root';
$user_password = '';

try {
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}
?>
