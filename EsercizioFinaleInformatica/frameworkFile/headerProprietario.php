<head>
    <link rel="stylesheet" href="/esercizioFinaleInformatica/frameworkFile/headerProprietario.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php
    $current_page = basename($_SERVER['PHP_SELF']); 
?>
<nav class="intestazioneProprietario">
    <div class="sitoNome">
        <a href="/esercizioFinaleInformatica/home.php">Futsal Mania</a>
        <span>by Cazzaro Riccardo</span>
    </div>

    <div class="links">
        <a href="/esercizioFinaleInformatica/adminFunzioni/classifiche.php" class="<?= ($current_page == "classifiche.php") ? "active" : ""; ?>">Classifiche</a>
        <a href="/esercizioFinaleInformatica/campiFile/campi.php" class="<?= ($current_page == "campi.php") ? "active" : ""; ?>">Campi</a>
        <a href="/esercizioFinaleInformatica/storiaFile/storia.php" class="<?= ($current_page == "storia.php") ? "active" : ""; ?>">Storia</a>
        <a href="/esercizioFinaleInformatica/shopFile/shop.php" class="<?= ($current_page == "shop.php") ? "active" : ""; ?>">Shop</a>
    </div>

    <div class="linkProprietario">
        <a href="/esercizioFinaleInformatica/adminFunzioni/gestione.php" class="<?= ($current_page == "gestione.php") ? "active" : ""; ?>">Gestione</a>
        <a href="/esercizioFinaleInformatica/adminFunzioni/creaCampionato.php" class="<?= ($current_page == "creaCampionato.php") ? "active" : ""; ?>">Campionati</a>
    </div>

    <div class="pulsantiUtente">
        <div class="salutoUtente">
            Ciao, <strong><?php echo htmlspecialchars($_SESSION["nome"]); ?></strong>
            <span class="badgeProprietario">Proprietario</span>
        </div>
        <a href="/esercizioFinaleInformatica/frameworkFile/logOut.php" class="logout">Esci</a>
    </div>
</nav>