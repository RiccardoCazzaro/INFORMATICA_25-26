<?php
session_start();
$pdo = DBHandler::getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO utenti (passwordUt, nome, cognome, email, telefono, provincia, CAP, dataNascita, nazionalità) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $passwordHash, 
            $_POST['nome'], 
            $_POST['cognome'], 
            $_POST['email'], 
            $_POST['telefono'], 
            $_POST['provincia'], 
            $_POST['CAP'], 
            $_POST['dataNascita'],
             $_POST['nazionalità']
        ]);

        // Login automatico dopo registrazione
        $nuovoIdUtente = $pdo->lastInsertId();
        $_SESSION['idUtente'] = $nuovoIdUtente;
        $_SESSION['nome'] = $_POST['nome']; 

        header("Location: ../home.php"); 
        exit();

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            header("Location: signupForm.php?errore=email_esistente");
            exit();
        } 
    }
} 
?>