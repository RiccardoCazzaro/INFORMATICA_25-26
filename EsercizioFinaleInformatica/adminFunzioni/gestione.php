<?php
$db = DBHandler::getPDO();

// AGGIUNTA SQUADRA
if (isset($_POST["add_squadra"])) { 
    $ctrDuplica = $db->prepare("SELECT COUNT(*) FROM squadra WHERE nomeSquadra=? AND idCampionato=?");
    $ctrDuplica->execute([$_POST["nome"], $_POST["idCampionato"]]); 

    if ($ctrDuplica->fetchColumn() > 0) {
        echo "<script>alert('Esiste già una squadra con questo nome in questo campionato!');location='gestione.php'</script>"; exit;
    }

    $db->prepare("INSERT INTO squadra (nomeSquadra, nomePalazzetto, coloriSocietari, nazionalità, città, idUtente, idCampionato, punti) VALUES (?,?,?,?,?,?,?,0)")
       ->execute([$_POST["nome"],$_POST["nomePalazzetto"],$_POST["coloriSocietari"],$_POST["nazionalità"],$_POST["città"],$_SESSION["idUtente"],$_POST["idCampionato"]]);
    header("Location: gestione.php"); 
    exit;
}


// ELIMINAZIONE SQUADRA
if (isset($_POST["del_squadra"])) {  
    $delId = $_POST["del_squadra"];
    $db->prepare("DELETE FROM squadra WHERE idSquadra=?")->execute([$delId]);
    header("Location: gestione.php"); 
    exit;
}


// AGGIUNTA PARTITA
if (isset($_POST["add_partita"])) {
    /*verif squad*/
    if ($_POST["c"] == $_POST["o"]) {
        echo "<script>alert('Le squadre devono essere diverse!');location='gestione.php'</script>"; exit;
    }

    $stmt = $db->prepare("SELECT idCampionato FROM squadra WHERE idSquadra=?");
    /*casa*/
    $stmt->execute([$_POST["c"]]);
    $campC = $stmt->fetchColumn();

    /*ospite*/
    $stmt->execute([$_POST["o"]]);
    $campO = $stmt->fetchColumn();

    /*verif camp*/
    if ($campC != $campO) {
        echo "<script>alert('Le squadre devono appartenere allo stesso campionato!');location='gestione.php'</script>"; exit;
    }

    $db->prepare("INSERT INTO partita (idSquadraCasa,idSquadraOspite,golSquadraCasa,golSquadraOspite,idUtente,idCampionato,dataPartita,oraPartita) VALUES (?,?,?,?,?,?,?,?)")
       ->execute([$_POST["c"],$_POST["o"],$_POST["gc"],$_POST["go"],$_SESSION["idUtente"],$campC,$_POST["data"],$_POST["ora"]]);

    $pc = $_POST["gc"]>$_POST["go"] ? 3 : ($_POST["gc"]==$_POST["go"] ? 1 : 0); // punti casa
    $po = $_POST["go"]>$_POST["gc"] ? 3 : ($_POST["go"]==$_POST["gc"] ? 1 : 0); // punti ospite

    $db->prepare("UPDATE squadra SET punti = punti + ? WHERE idSquadra=?")->execute([$pc,$_POST["c"]]); 
    $db->prepare("UPDATE squadra SET punti = punti + ? WHERE idSquadra=?")->execute([$po,$_POST["o"]]);
    header("Location: gestione.php"); exit;
}

$squadre = $db->query("SELECT s.*,c.nomeCampionato FROM squadra s INNER JOIN campionato c ON s.idCampionato=c.idCampionato ORDER BY s.punti DESC")->fetchAll();
$campionati = $db->query("SELECT * FROM campionato ORDER BY dataCreazione DESC")->fetchAll();

?>

<head>
    <link rel="stylesheet" href="gestione.css">
</head>
<body>
<div class="container">
    <h1>Gestione classifiche e squadre</h1>

    <!-- CLASSIFICA -->
    <section>
        <h3>Classifica</h3>
        <table>
            <tr><th>Pos</th><th>Squadra</th><th>Campionato</th><th>Punti</th><th>Azione</th></tr>           
            <?php foreach ($squadre as $i => $sDati){ ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($sDati['nomeSquadra']) ?></td>
                <td><?= htmlspecialchars($sDati['nomeCampionato']) ?></td>
                <td><strong><?= $sDati['punti'] ?></strong></td>

                <td><form method="POST">
                        <input  name="del_squadra" value="<?= $sDati['idSquadra'] ?>">
                        <button type="submit" onclick="return confirm('Eliminare questa squadra?')">🗑️</button>
                    </form></td>
            </tr>
            <?php } ?>
        </table>
    </section>



    <!-- NUOVA SQUADRA -->
    <section>
        <h3>Nuova Squadra</h3>
        <form method="POST">

            <select name="idCampionato" required>
                <option disabled selected> Scegli campionato </option>
                <?php foreach ($campionati as $c) { ?>
                    <option value="<?= $c['idCampionato'] ?>"> <?= htmlspecialchars($c['nomeCampionato']) ?></option>
                <?php } ?>
            </select>

            <input type="text" name="nome"            placeholder="Nome squadra"  required>
            <input type="text" name="nazionalità"     placeholder="Nazionalità">
            <input type="text" name="nomePalazzetto"  placeholder="Palazzetto">
            <input type="text" name="città"           placeholder="Città"         required>
            <input type="text" name="coloriSocietari" placeholder="Colori societari">
            <button name="add_squadra">Crea</button>
        </form>
    </section>



    <!-- NUOVA PARTITA -->
    <section>
        <h3>Nuova Partita</h3>
        <form method="POST">

            <select name="c" required>
                <option disabled selected>Casa</option>
                <?php foreach ($squadre as $s){ ?>
                    <option value="<?= $s['idSquadra'] ?>"><?= htmlspecialchars($s['nomeSquadra']) ?></option>
                <?php } ?>
            </select>

            <input type="number" name="gc" min="0" placeholder="Gol" required>

            VS

            <input type="number" name="go" min="0" placeholder="Gol" required>

            <select name="o" required>
                <option disabled selected>Ospite</option>
                <?php foreach ($squadre as $s) {?>
                    <option value="<?= $s['idSquadra'] ?>"> <?= htmlspecialchars($s['nomeSquadra']) ?></option>
                <?php } ?>
            </select>

            <input type="text" name="data" placeholder="Data" required
                onfocus="this.type='date'"
                onblur="if(!this.value)this.type='text'">

            <input type="text" name="ora" placeholder="Ora" required
                onfocus="this.type='time'"
                onblur="if(!this.value)this.type='text'">

            <button name="add_partita">Salva</button>
        </form>
    </section>
</div>
</body>