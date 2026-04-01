<?php
$db = DBHandler::getPDO();

// AGGIUNTA SQUADRA
if (isset($_POST['add_squadra'])) {

   // Salvataggio logo 
        $logoPath = null;
            if (!empty($_FILES['logo']['tmp_name'])) {
                 $nomeFile = time() . '_' . $_FILES['logo']['name'];
                 $destinazione = 'loghi/' . $nomeFile; 

                  if (!is_dir('loghi')) mkdir('loghi', 0755, true);

                     // Sposta il file e salva il percorso nel DB
                 if (move_uploaded_file($_FILES['logo']['tmp_name'], $destinazione)) {
                    $logoPath = $destinazione; 
                 }
                }

        $db->prepare("INSERT INTO squadra (nomeSquadra, logo, nomePalazzetto, coloriSocietari, nazionalità, città, dataNascita, idUtente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([$_POST['nome'], $logoPath, $_POST['nomePalazzetto'], $_POST['coloriSocietari'], $_POST['nazionalità'], $_POST['città'], $_POST['data'], $_SESSION['idUtente']]);

        $db->prepare("INSERT INTO classifica (idSquadra, punti) VALUES (?, 0)")->execute([$db->lastInsertId()]);   
        header("Location: gestione.php"); exit;
}



// ELIMINAZIONE SQUADRA
if (isset($_GET['del_squadra'])) {
    $delId = (int)$_GET['del_squadra'];

    $res = $db->prepare("SELECT logo FROM squadra WHERE idSquadra = ?");
    $res->execute([$delId]);
    $logo = $res->fetchColumn(); 

    if ($logo && file_exists(__DIR__ . '/loghi/' . basename($logo))) {
        unlink(__DIR__ . '/loghi/' . basename($logo));
    }

    $db->prepare("DELETE FROM squadra WHERE idSquadra = ?")->execute([$delId]);
    header("Location: gestione.php"); exit;
}



// AGGIUNTA PARTITA 
if (isset($_POST['add_partita'])) {
    if ($_POST['c'] == $_POST['o']) {
        echo "<script>alert('Squadre devono essere diverse!'); window.location.href = 'gestione.php';</script>";
        exit;
    }

    $db->prepare("INSERT INTO partita (idSquadraCasa, idSquadraOspite, golSquadraCasa, golSquadraOspite, idUtente) VALUES (?,?,?,?,?)")
    ->execute([$_POST['c'], $_POST['o'], $_POST['gc'], $_POST['go'], $_SESSION['idUtente']]);

    $pc = $_POST['gc'] > $_POST['go'] ? 3 : ($_POST['gc'] == $_POST['go'] ? 1 : 0);
    $po = $_POST['go'] > $_POST['gc'] ? 3 : ($_POST['go'] == $_POST['gc'] ? 1 : 0);

    // aggiorna punti in classifica 
    $db->prepare("UPDATE classifica SET punti = punti + ? WHERE idSquadra = ?")->execute([$pc, $_POST['c']]);
    $db->prepare("UPDATE classifica SET punti = punti + ? WHERE idSquadra = ?")->execute([$po, $_POST['o']]);

    header("Location: gestione.php"); exit;
}


// RECUPERO DATI
$squadre = $db->query("
    SELECT s.*, IFNULL(c.punti, 0) AS punti
    FROM squadra s
    LEFT JOIN classifica c ON s.idSquadra = c.idSquadra
    ORDER BY punti DESC
")->fetchAll();


?>


<head>
    <link rel="stylesheet" href="gestione.css">
</head>

<div class="container">
    <h1>Gestisci il tuo campionato</h1>

    <section>
        <h3>Classifica</h3>
        <table>
            <tr>
                <th>Pos</th>
                <th>Squadra</th>
                <th>Punti</th>
                <th>Azione</th>
            </tr>
            <?php foreach($squadre as $i => $s): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td>
                    <?php if (!empty($s['logo'])): ?>
                        <img src="<?= htmlspecialchars($s['logo']) ?>" class="logo-mini" alt="logo">
                    <?php else: ?>
                        <span class="logo-placeholder">⚽</span>
                    <?php endif; ?>

                    <?= htmlspecialchars($s['nomeSquadra']) ?>
                </td>

                <td><b><?= $s['punti'] ?></b></td>
                <td><a href="?del_squadra=<?= $s['idSquadra'] ?>" onclick="return confirm('Elimina?')">🗑️</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- AGGIUNGERE SQUADRA -->
    <section>
        <h3>Nuova Squadra</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text"  name="nome"            placeholder="Nome squadra"         required>
            <input type="text"  name="nazionalità"     placeholder="Nazionalità"           required>

            <input type="text"  name="data" placeholder="Data di fondazione" onfocus="(this.type='date')" 
                 onblur="if(!this.value) this.type='text'"  required>

            <div class="file">
                <p class="titoloFile">LOGO SQUADRA</p>
                <input type="file" name="logo" accept="image/*">
            </div>

            <input type="text"  name="nomePalazzetto"  placeholder="Nome palazzetto casa"  required>
            <input type="text"  name="città"           placeholder="Città"                 required>
            <input type="text"  name="coloriSocietari" placeholder="Colori societari"      required>
            <button name="add_squadra">Crea</button>
        </form>
    </section>


    <!-- AGGIUNGERE PARTITA -->
    <section>
        <h3>Nuova Partita</h3>
        <form method="POST">
            <select name="c">
                <?php foreach($squadre as $s) 
                    echo "<option value='{$s['idSquadra']}'>" . htmlspecialchars($s['nomeSquadra']) . "</option>"; 
                ?>
            </select>
            <input type="number" name="gc" min="0" placeholder="Gol" required>

            VS

            <input type="number" name="go" min="0" placeholder="Gol" required>
            <select name="o">
                <?php foreach($squadre as $s) 
                    echo "<option value='{$s['idSquadra']}'>" . htmlspecialchars($s['nomeSquadra']) . "</option>"; 
                ?>
            </select>
            <button name="add_partita">Salva</button>
        </form>
    </section>



</div>
