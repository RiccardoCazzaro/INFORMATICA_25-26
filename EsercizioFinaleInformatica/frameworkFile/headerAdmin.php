<head>
    <link rel="stylesheet" href="/esercizioFinaleInformatica/frameworkFile/headerAdmin.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php
    $current_page = basename($_SERVER['PHP_SELF']); 
?>

<nav class="intestazioneAdmin">
     <div class="sitoNome">
            <a href="/esercizioFinaleInformatica/home.php">Fustal Mania</a>
            <span>by Cazzaro Riccardo</span>
     </div>

   <div class="links">
        <a href="/esercizioFinaleInformatica/adminFunzioni/classifiche.php" class="<?= ($current_page == 'classifiche.php') ? 'active' : ''; ?>">Classifiche</a>
        <a href="/esercizioFinaleInformatica/campiFile/campi.php" class="<?= ($current_page == 'campi.php') ? 'active' : ''; ?>">Campi</a>
        <a href="/esercizioFinaleInformatica/storiaFile/storia.php" class="<?= ($current_page == 'storia.php') ? 'active' : ''; ?>">Storia</a>
        <a href="/esercizioFinaleInformatica/shopFile/shop.php" class="<?= ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a>
    </div>

    <div class="linkAdmin">
        <a href="/esercizioFinaleInformatica/adminFunzioni/gestione.php" class="<?= ($current_page == 'gestione.php') ? 'active' : ''; ?>">Gestione</a>
    </div>

    <div class="pulsantiUtente">
        <span class="salutoUtente">Ciao, <strong><?php echo htmlspecialchars($_SESSION['nome']); ?></strong></span>
        <a href="/esercizioFinaleInformatica/frameworkFile/logOut.php" class="logout">Esci</a>
    </div>
</nav>