<?php
require 'connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aggiorna_tutto'])) {
    $idPartita = $_POST['idPartita'];
    $golCasa = $_POST['golSquadraCasa'];
    $golOspite = $_POST['golSquadraOspite'];
    
    $idGiocatore = $_POST['idGiocatore']; 
    $minuto = $_POST['minuto']; 

    $sqlPartita = "UPDATE partita SET golSquadraCasa = :golCasa, golSquadraOspite = :golOspite WHERE idPartita = :idPartita";
    $stmt1 = $conn->prepare($sqlPartita);
    $stmt1->execute([
        ':golCasa' => $golCasa,
        ':golOspite' => $golOspite,
        ':idPartita' => $idPartita
    ]);

    if (!empty($idGiocatore) && !empty($minuto)) {
        $sqlMarcatore = "INSERT INTO marcatore (idPartita, idGiocatore, minuto) VALUES (:idPartita, :idGiocatore, :minuto)";
        $stmt2 = $conn->prepare($sqlMarcatore);
        $stmt2->execute([
            ':idPartita' => $idPartita,
            ':idGiocatore' => $idGiocatore,
            ':minuto' => $minuto
        ]);
        
        $sqlIncremento = "UPDATE giocatore SET goalSegnati = goalSegnati + 1 WHERE idGiocatore = :idGiocatore";
        $stmt3 = $conn->prepare($sqlIncremento);
        $stmt3->execute([':idGiocatore' => $idGiocatore]);
    }

    echo "Aggiornamento completato con successo!";
}
?>