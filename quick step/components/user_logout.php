<?php
// Šis kods nodrošina lietotāja izrakstīšanās funkcionalitāti. 
// Tas izbeidz pašreizējo sesiju un novirza lietotāju uz mājas lapu.

include 'connect.php';

session_start();
session_unset();
session_destroy();

header('location:../home.php');
exit;
?>
