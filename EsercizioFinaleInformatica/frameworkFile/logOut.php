<?php
session_start();

$_SESSION = array();   /*svuota dati*/

session_destroy();  /*elimina file su sevrer*/   /*esce*/

header("Location: ../home.php");
exit();
?>