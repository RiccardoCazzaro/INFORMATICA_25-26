<?php
require 'connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crea_partita'])) {
    $idCasa = $_POST['idSquadraCasa'];
    $idOspite = $_POST['idSquadraOspite'];

    $checkGiocatori = $conn->prepare("
        SELECT idSquadra, COUNT(*) as totale 
        FROM giocatore 
        WHERE idSquadra IN (?, ?) 
        GROUP BY idSquadra
    ");
    $checkGiocatori->execute([$idCasa, $idOspite]);
    $risultati = $checkGiocatori->fetchAll(PDO::FETCH_ASSOC);

    if (count($risultati) < 5) {
        die("Errore: Entrambe le squadre devono avere almeno 5 giocatori registrati.");
    }

    $sql = "INSERT INTO partita (idCampionato, idSquadraCasa, idSquadraOspite, dataPartita, oraPartita, golSquadraCasa, golSquadraOspite) 
            VALUES (?, ?, ?, ?, ?, 0, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $_POST['idCampionato'],
        $idCasa,
        $idOspite,
        $_POST['dataPartita'],
        $_POST['oraPartita']
    ]);

    echo "Partita creata con successo (Risultato iniziale 0-0).";
}
?>