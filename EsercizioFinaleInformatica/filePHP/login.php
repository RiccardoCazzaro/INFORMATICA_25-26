<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $passwordUt = $_POST['passwordUt'];

    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE  nome = ?, cognome = ? email = ?");
    $stmt->execute([$nome, $cognome, $email]);
    $utente = $stmt->fetch();

    if ($utente && password_verify($pass, $utente['passwordUt'])) {
        $_SESSION['idUtente'] = $utente['id'];
        $_SESSION['nome'] = $utente['nome'];
        $_SESSION['cognome'] = $utente['cognome'];
        $_SESSION['email'] = $utente['email'];

        header("Location: dashboard.php");
        exit();
    } else {
        $errore = "Credenziali errate";
    }
}
?>

<form method="POST">
    <h2>Login</h2>
    <?php if(isset($errore)) echo "<p style='color:red'>$errore</p>"; ?>
    <input type="text" name="nome" placeholder="nome" required><br>
    <input type="text" name="cognome" placeholder="cognome" required><br>
    <input type="passwordUt" name="passwordUt" placeholder="Password" required><br>
    <button type="submit">Entra</button>
</form>