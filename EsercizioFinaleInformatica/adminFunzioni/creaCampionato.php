<?php
$db = DBHandler::getPDO();

// CREA CAMPIONATO
if (isset($_POST["creaTorneo"])) {

    $nome = trim($_POST["nomeCampionato"]);
    /*se nome e =*/
    $ctrduplica = $db->prepare("SELECT COUNT(*) FROM campionato WHERE nomeCampionato=?"); 
    $ctrduplica->execute([$nome]);

    if ($ctrduplica->fetchColumn() > 0) {
        echo "<script>alert('Esiste già un campionato con questo nome!');location='creaCampionato.php'</script>"; exit;
    }

    /*aggiunge*/
    $db->prepare("INSERT INTO campionato (nomeCampionato, dataCreazione) VALUES (?, NOW())")  /*now  data di oggi*/
       ->execute([$nome]);
    header("Location: creaCampionato.php"); exit;
}

// ELIMINA CAMPIONATO
if (isset($_POST["del"])) {
    $delId = $_POST["del"];
    $db->prepare("DELETE FROM campionato WHERE idCampionato = ?")->execute([$delId]);
    header("Location: creaCampionato.php"); exit;
}

// LISTA CAMPIONATI
$campionati = $db->query("SELECT * FROM campionato ORDER BY dataCreazione DESC")->fetchAll();
?>


<head>
    <link rel="stylesheet" href="creaCampionato.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
    <h1>Campionati</h1>

    <!-- FORM CREAZIONE -->
    <section class="aggiungi">
        <h3>Nuovo Campionato</h3>
        <form method="POST" class="sezione">
            <input type="text" name="nomeCampionato" placeholder="Es. Serie A" required>
            <button name="creaTorneo">Crea</button>
        </form>
    </section>

    <!-- LISTA -->
    <section>
        <h3>Campionati Esistenti</h3>
        <!--array vuoto-->
        <?php if (!$campionati){?>
            <p class="vuoto">Nessun campionato creato.</p>
        <?php }   
        else { ?>
            <div class="lista">
                <?php foreach ($campionati as $c) { ?>
                <div class="riga">
                    <div class="info">
                        <span class="nome"><?= htmlspecialchars($c["nomeCampionato"]) ?></span>

                        <?php   
                            $data = new DateTime($c["dataCreazione"]);
                            echo $data->format("d/m/Y"); 
                        ?>
                        
                    </div>
                       <form method="POST">
                            <input  type="hidden" name="del" value="<?= $c["idCampionato"] ?>">
                            <button type="submit" type="hidden" class="del" onclick="return confirm('Eliminare «<?= htmlspecialchars($c['nomeCampionato']) ?> »?')">
                                🗑 Elimina
                            </button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        <?php }  ?>
    </section>
    </div>
</body>