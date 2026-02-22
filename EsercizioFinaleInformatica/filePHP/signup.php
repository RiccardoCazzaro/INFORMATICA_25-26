<?php
    session_start(); 
    require_once 'connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_exists = false;

        $passwordUt = password_hash($_POST['passwordUt'], PASSWORD_DEFAULT); 
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $dataNascita = $_POST['dataNascita'];
        $CAP = $_POST['CAP'];
        $provincia = $_POST['provincia'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $nazionalità = $_POST['nazionalità'];


        if ($conn) {
            $userPresente = $conn->prepare('SELECT COUNT(*) FROM utenti WHERE passwordUt = :$passwordUt');
            $userPresente->bindParam(":passowrdUt", $passwordUt);
            $userPresente->execute();
            
            if ($userPresente->fetchColumn() > 0) {
                die("L'utente esiste già");
            } else {
                $val = $conn->prepare('INSERT INTO utenti (passwordUt, nome, cognome, idUtente, telefono, provincia, email, dataNascita, nazionalità, CAP)
                 VALUES (:passwordUt, :nome, :cognome, :idUtente, :indirizzo, :dataNascita, :tipologiaSocio)');
                
                $val->bindParam(':passwordUt', $passwordUt);
                $val->bindParam(':nome', $nome);
                $val->bindParam(':cognome', $cognome);
                $val->bindParam(':idUtente', $idUtente);
                $val->bindParam(':telefono', $telefono);
                $val->bindParam(':provincia', $provincia);
                $val->bindParam(':email', $email);
                $val->bindParam(':dataNascita', $dataNascita);
                $val->bindParam(':CAP', $CAP);
                $val->bindParam(':nazionalità', $nazionalità);
                
                
                if ($val->execute()) {
                    $_SESSION['user'] = [
                        'idUtente' => $idUtente,
                        'nome' => $nome,
                        'cognome' => $cognome
                    ];
                    echo("Benvenuto " . htmlspecialchars($nome) . " " . htmlspecialchars($cognome));
                } else {
                    print("Errore nell'inserimento dell'utente");
                }
            }
        }
    }
?>