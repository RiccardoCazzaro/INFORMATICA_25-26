<head>
    <link rel="stylesheet" href="/esercizioFinaleInformatica/frameworkFile/headerAdmin.css?v=<?php echo time(); ?>">
</head>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$nomeAdmin = isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : "Admin";
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<nav class="intestazioneAdmin">
     <div class="linkHome">
            <a href="/esercizioFinaleInformatica/home.php">Fustal World</a>
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
    </li>

    <div class="pulsantiUtente">
        <span class="salutoUtente">Ciao, <strong><?php echo $_SESSION['nome']; ?></strong></span>
        <a href="/esercizioFinaleInformatica/frameworkFile/logOut.php" class="logout">Esci</a>
    </div>
</nav>