<?php
$db = DBHandler::getPDO();

$idCampionato = isset($_GET["torneo"]) ? $_GET["torneo"] : 0;

$campionati = $db->query("SELECT * FROM campionato ORDER BY dataCreazione DESC")->fetchAll();

/*se si ha scelto un campionato*/
/* classifica  */
if ($idCampionato > 0) {
    $qClass = $db->prepare("
        SELECT s.*
        FROM squadra s
        WHERE s.idCampionato = ?
        ORDER BY s.punti DESC
    ");
    $qClass->execute([$idCampionato]);
} else {
    $qClass = $db->query("
        SELECT s.*
        FROM squadra s
        ORDER BY s.punti DESC
    ");
}
$classifica = $qClass->fetchAll();

/*partite*/
if ($idCampionato > 0) {
    $qP = $db->prepare("
        SELECT p.*,
            s1.nomeSquadra AS nomeCasa,    s1.idSquadra AS idCasa,
            s2.nomeSquadra AS nomeOspite,  s2.idSquadra AS idOspite
        FROM partita p
        JOIN squadra s1 ON p.idSquadraCasa    = s1.idSquadra
        JOIN squadra s2 ON p.idSquadraOspite  = s2.idSquadra
        WHERE p.idCampionato = ?
        ORDER BY p.idPartita DESC
    ");
    $qP->execute([$idCampionato]);
} else {
    $qP = $db->query("
        SELECT p.*,
            s1.nomeSquadra AS nomeCasa, s1.idSquadra AS idCasa,
            s2.nomeSquadra AS nomeOspite, s2.idSquadra AS idOspite
        FROM partita p
        JOIN squadra s1 ON p.idSquadraCasa = s1.idSquadra
        JOIN squadra s2 ON p.idSquadraOspite  = s2.idSquadra
        ORDER BY p.idPartita DESC
    ");
}
$partite = $qP->fetchAll();
?>

<head>
    <link rel="stylesheet" href="/esercizioFinaleInformatica/adminFunzioni/classifiche.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="header">
    <h1>Classifica e Risultati</h1>
</div>

<div class="container">

    <!--  CAMPIONATO -->
    <div class="campionatoClassifica">
        <form method="GET" class="sceltaCampionato">

            <label class="campionato">Campionato:</label>
            <select name="torneo" onchange="this.form.submit()"> 
                <option value="" <?= !$idCampionato ? "selected" : "" ?>>Tutti</option>
                <?php foreach ($campionati as $c) { ?>
                    <option value="<?= $c["idCampionato"] ?>" <?= $idCampionato == $c["idCampionato"] ? "selected" : "" ?>>
                        <?= htmlspecialchars($c["nomeCampionato"]) ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>

    <div class="buttons">
        <button class="tab-btn on" onclick="tab('classifica', this)">Classifica</button>
        <button class="tab-btn"   onclick="tab('risultati',  this)">Risultati</button>
    </div>

    <!-- TAB CLASSIFICA -->
    <div id="classifica" class="tab on">
        <?php if (!$classifica): ?>
            <p class="vuoto">Nessuna squadra registrata.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>#</th><th>Squadra</th><th>PG</th><th>GF</th>
                    <th>GS</th><th>V</th><th>P</th><th>S</th><th>DR</th><th>Punti</th>
                </tr>
            <tbody>
                <?php foreach ($classifica as $i => $squadra):

                    /*partite giocate*/
                    $q = $db->prepare("SELECT COUNT(idPartita) FROM partita WHERE idSquadraCasa = ? OR idSquadraOspite = ?");
                    $q->execute([$squadra["idSquadra"], $squadra["idSquadra"]]);
                    $pg = $q->fetchColumn();


                    /*gol fatti*/  /* query null --> 0*/
                    $q = $db->prepare("SELECT
                        IFNULL((SELECT SUM(golSquadraCasa)   FROM partita WHERE idSquadraCasa   = ?), 0) +
                        IFNULL((SELECT SUM(golSquadraOspite) FROM partita WHERE idSquadraOspite = ?), 0)");
                    $q->execute([$squadra["idSquadra"], $squadra["idSquadra"]]);
                    $gf = $q->fetchColumn();

                    /*gol subiti*/
                    $q = $db->prepare("SELECT
                        IFNULL((SELECT SUM(golSquadraOspite) FROM partita WHERE idSquadraCasa   = ?), 0) +
                        IFNULL((SELECT SUM(golSquadraCasa)   FROM partita WHERE idSquadraOspite = ?), 0)");
                    $q->execute([$squadra["idSquadra"], $squadra["idSquadra"]]);
                    $gs = $q->fetchColumn();

                    /*partite vinte*/
                    $q = $db->prepare("SELECT COUNT(idPartita) FROM partita WHERE
                        (idSquadraCasa = ? AND golSquadraCasa> golSquadraOspite) OR
                        (idSquadraOspite = ? AND golSquadraOspite > golSquadraCasa)");
                    $q->execute([$squadra["idSquadra"], $squadra["idSquadra"]]);
                    $v = $q->fetchColumn();

                    /*partite pareggiate*/
                    $q = $db->prepare("SELECT COUNT(idPartita) FROM partita WHERE
                        (idSquadraCasa = ? OR idSquadraOspite = ?) AND golSquadraCasa = golSquadraOspite");
                    $q->execute([$squadra["idSquadra"], $squadra["idSquadra"]]);
                    $par = $q->fetchColumn();

                    /*partite sconfitte*/
                    $sconf = $pg - $v - $par;

                    /*differenza reti*/
                    $dr = $gf - $gs;
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($squadra["nomeSquadra"]) ?></td>
                    <td><?= $pg ?></td>
                    <td><?= $gf ?></td>
                    <td><?= $gs ?></td>
                    <td class="v"><?= $v ?></td>
                    <td class="g"><?= $par ?></td>
                    <td class="r"><?= $sconf ?></td>
                    <td><?= $dr ?></td>
                    <td><b><?= $squadra["punti"] ?></b></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        <?php endif; ?>
    </div>
    

<!-- TAB RISULTATI -->
<div id="risultati" class="tab">
    <?php if (!$partite): ?>
        <p class="vuoto">Nessuna partita disputata.</p>
    <?php else: ?>
    <div class="griglia">
        <?php foreach ($partite as $p) { ?>
        <div class="card">
            <div class="dataOra">
                <?= date("d/m/Y", strtotime($p["dataPartita"])) ?>
                <?= " – " . substr($p["oraPartita"], 0, 5) ?> <!-- da 0 al quinto caratter"-->
            </div>
            <div class="sfida">
                <span><?= htmlspecialchars($p["nomeCasa"]) ?></span>
                <b><?= $p["golSquadraCasa"] ?> – <?= $p["golSquadraOspite"] ?></b>
                <span><?= htmlspecialchars($p["nomeOspite"]) ?></span>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php endif; ?>
</div>

</div>

<script>
function tab(id, b) {
    document.querySelectorAll(".tab").forEach(t => t.classList.remove("on")); /*tutte le tab togli la classe on*/
    document.querySelectorAll(".tab-btn").forEach(x => x.classList.remove("on"));/*stesso per i bottoni*/
    document.getElementById(id).classList.add("on"); /*alla tab con id=id aggiungi classe on*/
    b.classList.add("on"); /*al bottone cliccato aggiungi classe on*/
}
</script>

</body>
