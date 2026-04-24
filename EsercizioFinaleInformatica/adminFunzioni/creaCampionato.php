<?php
$db = DBHandler::getPDO();

// CREA CAMPIONATO
if (isset($_POST['creaTorneo'])) {
    $nome = trim($_POST['nome_torneo']);
    $ctrduplica = $db->prepare("SELECT COUNT(*) FROM campionato WHERE nomeCampionato=?"); 
    $ctrduplica->execute([$nome]);
    if ($ctrduplica->fetchColumn() > 0) {
        echo "<script>alert('Esiste già un campionato con questo nome!');location='creaCampionato.php'</script>"; exit;
    }
    $db->prepare("INSERT INTO campionato (nomeCampionato, dataCreazione) VALUES (?, NOW())")  /*now  data di ora*/
       ->execute([$nome]);
    header("Location: creaCampionato.php"); exit;
}

// ELIMINA CAMPIONATO
if (isset($_GET['del'])) {
    $delId = (int)$_GET['del'];
    $db->prepare("DELETE FROM campionato WHERE idCampionato = ?")->execute([$delId]);
    header("Location: creaCampionato.php"); exit;
}

// LISTA CAMPIONATI
$tornei = $db->query("SELECT * FROM campionato ORDER BY dataCreazione DESC")->fetchAll();
?>


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="creaCampionato.css">
</head>
<body>

<div class="container">
    <h1>Campionati</h1>

    <!-- FORM CREAZIONE -->
    <section class="aggiungi">
        <h3>Nuovo Campionato</h3>
        <form method="POST" class="sezione">
            <input type="text" name="nome_torneo" placeholder="Es. Serie A" required>
            <button name="creaTorneo">Crea</button>
        </form>
    </section>

    <!-- LISTA -->
    <section>
        <h3>Campionati Esistenti</h3>
        <?php if (!$tornei): ?>
            <p class="vuoto">Nessun campionato creato.</p>
        <?php else: ?>
            <div class="lista">
                <?php foreach ($tornei as $t): ?>
                <div class="riga">
                    <div class="info">
                        <span class="nome"><?= htmlspecialchars($t['nomeCampionato']) ?></span>

                        <?php   
                        $data = new DateTime($t['dataCreazione']);
                        echo $data->format('d/m/Y'); 
                        ?>
                        
                    </div>
                    <a href="?del=<?= $t['idCampionato'] ?>"
                       class="del"
                       onclick="return confirm('Eliminare «<?= htmlspecialchars($t['nomeCampionato']) ?>»?')">
                        🗑 Elimina
                    </a>
                </div>
                    <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
</body>