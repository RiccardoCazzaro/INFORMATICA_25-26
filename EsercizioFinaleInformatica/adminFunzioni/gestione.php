<?php
$db = DBHandler::getPDO();

// AGGIUNTA SQUADRA
if (isset($_POST['add_squadra'])) {
    // CONTROLLO DUPLICATO: verifica se esiste già una squadra con lo stesso nome nello stesso campionato
    $ctrDuplica = $db->prepare("SELECT COUNT(*) FROM squadra WHERE nomeSquadra=? AND idCampionato=?");
    $ctrDuplica->execute([$_POST['nome'], $_POST['idCampionato']]);
    if ($ctrDuplica->fetchColumn() > 0) {
        echo "<script>alert('Esiste già una squadra con questo nome in questo campionato!');location='gestione.php'</script>"; exit;
    }
    $logo = null;
    if (!empty($_FILES['logo']['tmp_name'])) {
        if (!is_dir('loghi')) mkdir('loghi', 0755, true);
        $dest = 'loghi/' . time() . '_' . $_FILES['logo']['name'];
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $dest)) $logo = $dest;
    }
    $db->prepare("INSERT INTO squadra (nomeSquadra,logo,nomePalazzetto,coloriSocietari,nazionalità,città,dataNascita,idUtente,idCampionato,punti) VALUES (?,?,?,?,?,?,?,?,?,0)")
       ->execute([$_POST['nome'],$logo,$_POST['nomePalazzetto'],$_POST['coloriSocietari'],$_POST['nazionalità'],$_POST['città'],$_POST['data'],$_SESSION['idUtente'],$_POST['idCampionato']?:null]);
    header("Location: gestione.php"); exit;
}

// ELIMINAZIONE SQUADRA
if (isset($_GET['del_squadra'])) {
    $delId = (int)$_GET['del_squadra'];
    $stmt = $db->prepare("SELECT logo FROM squadra WHERE idSquadra=?");
    $stmt->execute([$delId]);
    $logoFile = $stmt->fetchColumn();
    if ($logoFile && file_exists(__DIR__.'/loghi/'.basename($logoFile))) unlink(__DIR__.'/loghi/'.basename($logoFile));
    $db->prepare("DELETE FROM squadra WHERE idSquadra=?")->execute([$delId]);
    header("Location: gestione.php"); exit;
}

// AGGIUNTA PARTITA
if (isset($_POST['add_partita'])) {
    if ($_POST['c'] == $_POST['o']) {
        echo "<script>alert('Le squadre devono essere diverse!');location='gestione.php'</script>"; exit;
    }
    $stmt = $db->prepare("SELECT idCampionato FROM squadra WHERE idSquadra=?");
    $stmt->execute([$_POST['c']]); $campC = $stmt->fetchColumn();
    $stmt->execute([$_POST['o']]); $campO = $stmt->fetchColumn();
    if ($campC != $campO) {
        echo "<script>alert('Le squadre devono appartenere allo stesso campionato!');location='gestione.php'</script>"; exit;
    }
    $db->prepare("INSERT INTO partita (idSquadraCasa,idSquadraOspite,golSquadraCasa,golSquadraOspite,idUtente,idCampionato) VALUES (?,?,?,?,?,?)")
       ->execute([$_POST['c'],$_POST['o'],$_POST['gc'],$_POST['go'],$_SESSION['idUtente'],$campC]);
    $pc = $_POST['gc']>$_POST['go'] ? 3 : ($_POST['gc']==$_POST['go'] ? 1 : 0); // punti casa
    $po = $_POST['go']>$_POST['gc'] ? 3 : ($_POST['go']==$_POST['gc'] ? 1 : 0); // punti ospite
    $db->prepare("UPDATE squadra SET punti=punti+? WHERE idSquadra=?")->execute([$pc,$_POST['c']]); 
    $db->prepare("UPDATE squadra SET punti=punti+? WHERE idSquadra=?")->execute([$po,$_POST['o']]);
    header("Location: gestione.php"); exit;
}

$squadre    = $db->query("SELECT s.*,c.nomeCampionato FROM squadra s LEFT JOIN campionato c ON s.idCampionato=c.idCampionato ORDER BY s.punti DESC")->fetchAll();
$campionati = $db->query("SELECT * FROM campionato ORDER BY dataCreazione DESC")->fetchAll();
?>
<head>
    <link rel="stylesheet" href="gestione.css">
</head>
<div class="container">
    <h1>Gestione classifiche e squadre</h1>

    <!-- CLASSIFICA -->
    <section>
        <h3>Classifica</h3>
        <table>
            <tr><th>Pos</th><th>Squadra</th><th>Campionato</th><th>Punti</th><th>Azione</th></tr>
            <?php foreach ($squadre as $i => $s){ ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td>
                    <?php if (!empty($s['logo'])){ ?>
                    <img src="<?= htmlspecialchars($s['logo']) ?>" class="logo-mini">
                    <?php } 
                    else { ?>
                    <span class="placeholder">⚽</span>
                    <?php } ?>
                    <?= htmlspecialchars($s['nomeSquadra']) ?>
                </td>
                <td><?= !empty($s['nomeCampionato']) ? '<span class="tag-torneo">'.htmlspecialchars($s['nomeCampionato']).'</span>' : '—' ?></td>
                <td><b><?= $s['punti'] ?></b></td>
                <td><a href="?del_squadra=<?= $s['idSquadra'] ?>" onclick="return confirm('Eliminare questa squadra?')">🗑️</a></td>
            </tr>
            <?php } ?>
        </table>
    </section>

    <!-- NUOVA SQUADRA -->
    <section>
        <h3>Nuova Squadra</h3>
        <form method="POST" enctype="multipart/form-data">

            <select name="idCampionato" required>
                <option value="" disabled selected>Scegli campionato</option>
                <?php foreach ($campionati as $c) { ?>
                    <option value="<?= $c['idCampionato'] ?>"><?= htmlspecialchars($c['nomeCampionato']) ?></option>
                <?php } ?>
            </select>

            <input type="text"   name="nome"            placeholder="Nome squadra"       required>
            <input type="text"   name="nazionalità"     placeholder="Nazionalità">
            <input type="text"   name="data"            placeholder="Data di fondazione"
                   onfocus="this.type='date'" onblur="if(!this.value)this.type='text'">

            <div class="file">
                <p class="titoloFile">LOGO SQUADRA</p>
                <input type="file" name="logo" accept="image/*">
            </div>

            <input type="text"   name="nomePalazzetto"  placeholder="Nome palazzetto casa">
            <input type="text"   name="città"           placeholder="Città"              required>
            <input type="text"   name="coloriSocietari" placeholder="Colori societari">
            <button name="add_squadra">Crea</button>
        </form>
    </section>

    <!-- NUOVA PARTITA -->
    <section>
        <h3>Nuova Partita</h3>
        <form method="POST">
            <select name="c">
                <option value="" disabled selected>Casa</option>
                <?php foreach ($squadre as $s){ ?>
                    <option value="<?= $s['idSquadra'] ?>"><?= htmlspecialchars($s['nomeSquadra']) ?></option>
                <?php } ?>
            </select>
            <input type="number" name="gc" min="0" placeholder="Gol" required>
            VS
            <input type="number" name="go" min="0" placeholder="Gol" required>
            <select name="o">
                <option value="" disabled selected>Ospite</option>
                <?php foreach ($squadre as $s) {?>
                    <option value="<?= $s['idSquadra'] ?>"><?= htmlspecialchars($s['nomeSquadra']) ?></option>
                <?php } ?>
            </select>
            <button name="add_partita">Salva</button>
        </form>
    </section>
</div>