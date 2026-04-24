<?php
$db = DBHandler::getPDO();

// Campionato selezionato (0 = tutti)
$idCampionato = isset($_GET['torneo']) ? (int)$_GET['torneo'] : 0;

// Lista campionati per il dropdown
$campionati = $db->query("SELECT * FROM campionato ORDER BY dataCreazione DESC")->fetchAll();

// Classifica (filtrata per campionato se selezionato)
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

// Partite (filtrate per campionato se selezionato)
if ($idCampionato > 0) {
    $qP = $db->prepare("
        SELECT p.*,
            s1.nomeSquadra AS nomeCasa,    s1.idSquadra AS idCasa,    s1.logo AS logoCasa,
            s2.nomeSquadra AS nomeOspite,  s2.idSquadra AS idOspite,  s2.logo AS logoOspite
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
            s1.nomeSquadra AS nomeCasa,    s1.idSquadra AS idCasa,    s1.logo AS logoCasa,
            s2.nomeSquadra AS nomeOspite,  s2.idSquadra AS idOspite,  s2.logo AS logoOspite
        FROM partita p
        JOIN squadra s1 ON p.idSquadraCasa    = s1.idSquadra
        JOIN squadra s2 ON p.idSquadraOspite  = s2.idSquadra
        ORDER BY p.idPartita DESC
    ");
}
$partite = $qP->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/esercizioFinaleInformatica/adminFunzioni/classifiche.css?v=<?= time() ?>">
    <title>Classifiche</title>
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
                <option value="0" <?= $idCampionato == 0 ? 'selected' : '' ?>>Tutti</option>
                <?php foreach ($campionati as $c) { ?>
                    <option value="<?= $c['idCampionato'] ?>" <?= $idCampionato == $c['idCampionato'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nomeCampionato']) ?>
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

                    $q = $db->prepare("SELECT COUNT(*) FROM partita WHERE idSquadraCasa = ? OR idSquadraOspite = ?");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $pg = $q->fetchColumn();

                    $q = $db->prepare("SELECT
                        IFNULL((SELECT SUM(golSquadraCasa)   FROM partita WHERE idSquadraCasa   = ?), 0) +
                        IFNULL((SELECT SUM(golSquadraOspite) FROM partita WHERE idSquadraOspite = ?), 0)");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $gf = $q->fetchColumn();

                    $q = $db->prepare("SELECT
                        IFNULL((SELECT SUM(golSquadraOspite) FROM partita WHERE idSquadraCasa   = ?), 0) +
                        IFNULL((SELECT SUM(golSquadraCasa)   FROM partita WHERE idSquadraOspite = ?), 0)");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $gs = $q->fetchColumn();

                    $q = $db->prepare("SELECT COUNT(*) FROM partita WHERE
                        (idSquadraCasa   = ? AND golSquadraCasa   > golSquadraOspite) OR
                        (idSquadraOspite = ? AND golSquadraOspite > golSquadraCasa)");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $v = $q->fetchColumn();

                    $q = $db->prepare("SELECT COUNT(*) FROM partita WHERE
                        (idSquadraCasa = ? OR idSquadraOspite = ?) AND golSquadraCasa = golSquadraOspite");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $par = $q->fetchColumn();

                    $sconf = $pg - $v - $par;
                    $dr = $gf - $gs;
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        
                            <?php if (!empty($squadra['logo'])): ?>
                                <img src="<?= htmlspecialchars($squadra['logo']) ?>" class="logo-mini">
                            <?php else: ?>
                                <span class="logo-placeholder">⚽</span>
                            <?php endif; ?>
                            <?= htmlspecialchars($squadra['nomeSquadra']) ?>
                        </a>
                    </td>
                    <td><?= $pg ?></td>
                    <td><?= $gf ?></td>
                    <td><?= $gs ?></td>
                    <td class="v"><?= $v ?></td>
                    <td class="g"><?= $par ?></td>
                    <td class="r"><?= $sconf ?></td>
                    <td><?= $dr ?></td>
                    <td><b><?= $squadra['punti'] ?></b></td>
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
            <?php foreach ($partite as $p) {  ?>
            <div class="card">
                <div class="sfida">
                    <a href="/esercizioFinaleInformatica/adminFunzioni/gestioneSquadra.php?id=<?= $p['idCasa'] ?>">
                        <?php if (!empty($p['logoCasa'])): ?>
                            <img src="<?= htmlspecialchars($p['logoCasa']) ?>" class="logoMini">
                        <?php endif; ?>
                        <?= htmlspecialchars($p['nomeCasa']) ?>
                    </a>
                    <b><?= $p['golSquadraCasa'] ?> – <?= $p['golSquadraOspite'] ?></b>
                    <a href="/esercizioFinaleInformatica/adminFunzioni/gestioneSquadra.php?id=<?= $p['idOspite'] ?>">
                        <?= htmlspecialchars($p['nomeOspite']) ?>
                        <?php if (!empty($p['logoOspite'])): ?>
                            <img src="<?= htmlspecialchars($p['logoOspite']) ?>" class="logoMini">
                        <?php endif; ?>
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
function tab(id, b) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('on'));
    document.querySelectorAll('.tab-btn').forEach(x => x.classList.remove('on'));
    document.getElementById(id).classList.add('on');
    b.classList.add('on');
}
</script>

</body>
</html>