
<?php
$db = DBHandler::getPDO();

// punti (fatto in tab classifica in base a id squadra)
$classifica = $db->query("
    SELECT s.*, IFNULL(c.punti, 0) AS punti
    FROM squadra s
    LEFT JOIN classifica c ON s.idSquadra = c.idSquadra
    ORDER BY punti DESC
")->fetchAll();

// Partite
$partite = $db->query("
    SELECT
        p.*,
        s1.nomeSquadra nomeCasa,   s1.idSquadra idCasa,   s1.logo logoCasa,
        s2.nomeSquadra nomeOspite, s2.idSquadra idOspite, s2.logo logoOspite
    FROM partita p
    JOIN squadra s1 ON p.idSquadraCasa   = s1.idSquadra
    JOIN squadra s2 ON p.idSquadraOspite = s2.idSquadra
    ORDER BY p.idPartita DESC
")->fetchAll();
?>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/esercizioFinaleInformatica/adminFunzioni/classifiche.css?v=<?= time() ?>">
</head>



    <div class="header">
        <p>Classifica e Risultati</p>
    </div>
    
<div class="container">

    <div class="buttons">
        <button class="tab-btn on" onclick="tab('classifica', this)">Classifica</button>
        <button class="tab-btn"   onclick="tab('risultati', this)">Risultati</button>
    </div>

    <!-- TAB CLASSIFICA -->
    <div id="classifica" class="tab on">
        <?php if (!$classifica): ?>
            <p class="vuoto">Nessuna squadra registrata.</p>
        <?php else: ?>
                <table>
                    <tr>
                         <th>#</th>
                         <th>Squadra</th>
                         <th>PG</th>
                         <th>GF</th>
                         <th>GS</th>
                         <th>V</th>
                         <th>P</th>
                         <th>S</th>
                         <th>DR</th>
                         <th>Punti</th>
                    </tr>
                    
                <tbody>
                  <?php foreach ($classifica as $i => $squadra):

                    // Partite giocate
                    $q = $db->prepare("SELECT COUNT(*) FROM partita WHERE idSquadraCasa = ? OR idSquadraOspite = ?");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $pg = $q->fetchColumn();

                    // Gol fatti
                    $q = $db->prepare("SELECT
                        (SELECT IFNULL(SUM(golSquadraCasa),   0) FROM partita WHERE idSquadraCasa   = ?) +
                        (SELECT IFNULL(SUM(golSquadraOspite), 0) FROM partita WHERE idSquadraOspite = ?)");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $gf = $q->fetchColumn();

                    // Gol subiti
                    $q = $db->prepare("SELECT
                        (SELECT IFNULL(SUM(golSquadraOspite), 0) FROM partita WHERE idSquadraCasa   = ?) +
                        (SELECT IFNULL(SUM(golSquadraCasa),   0) FROM partita WHERE idSquadraOspite = ?)");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $gs = $q->fetchColumn();

                    // Vittorie
                    $q = $db->prepare("SELECT COUNT(*) FROM partita WHERE
                        (idSquadraCasa = ? AND golSquadraCasa > golSquadraOspite) OR
                        (idSquadraOspite = ? AND golSquadraOspite > golSquadraCasa)");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $v = $q->fetchColumn();

                    // Pareggi
                    $q = $db->prepare("SELECT COUNT(*) FROM partita WHERE
                        (idSquadraCasa = ? OR idSquadraOspite = ?) AND golSquadraCasa = golSquadraOspite");
                    $q->execute([$squadra['idSquadra'], $squadra['idSquadra']]);
                    $par = $q->fetchColumn();

                    $dr = $gf - $gs; //diff reti
                ?>

                <tr>
                    <td><?= $i + 1 ?></td> <!-- pos -->
                    <td> <!-- logo e nome squad -->
                        <a href="/esercizioFinaleInformatica/adminFunzioni/gestioneSquadra.php?id=<?= $squadra['idSquadra'] ?>" class="squadraLink">
                            <?php if (!empty($squadra['logo'])): ?>
                                <img src="<?= htmlspecialchars($squadra['logo']) ?>" class="logo-mini">
                            <?php else: ?>
                                <span class="logo-placeholder">⚽</span>
                            <?php endif; ?>
                            <?= htmlspecialchars($squadra['nomeSquadra']) ?>
                        </a>
                    </td>
                    <td><?= $pg ?></td> <!-- partite giocate -->
                    <td><?= $gf ?></td> <!--gol fatti -->
                    <td><?= $gs ?></td> <!-- gol subiti -->
                    <td class="v"><?= $v ?></td> <!-- vittorie -->
                    <td class="g"><?= $par ?></td> <!-- pareggi -->
                    <td class="r"><?= $pg - $v - $par ?></td> <!-- sconfitte -->
                    <td><?= $dr ?></td> <!-- diff reti -->
                    <td><b><?= $squadra['punti'] ?></b></td> <!-- punti -->
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>
    </div>

    <!-- TAB RISULTATI -->
    <div id="risultati" class="tab">
        <?php if (!$partite): ?>
            <p class="vuoto">Nessuna partita disputata.</p>
        <?php else: ?>
        <div class="griglia">
            <?php foreach ($partite as $p): ?>
                <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>






<!-- Script per cambiare da classifica a partite fatte (ancora da fa) per attivare quella pag quando schiaccio -->
<script>
function tab(id, b) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('on')); 
    document.querySelectorAll('.tab-btn').forEach(x => x.classList.remove('on'));
    document.getElementById(id).classList.add('on');
    b.classList.add('on');
}
</script>