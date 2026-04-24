<?php
session_start();
$pdo = DBHandler::getPDO();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE email = ?"); // ? per evitare sql injection  --> divide query con di fatto la stringa
    $stmt->execute([$email]);
    $utente = $stmt->fetch();

    if ($utente && password_verify($password, $utente['passwordUt'])) {
        $_SESSION['idUtente'] = $utente['idUtente']; 
        $_SESSION['nome'] = $utente['nome'];      
        $_SESSION['ruolo'] = $utente['ruolo'];

        header("Location: ../home.php"); 
        exit();
    } else {
        header("Location: loginForm.php?errore=1");
        exit();
    }
}
?>