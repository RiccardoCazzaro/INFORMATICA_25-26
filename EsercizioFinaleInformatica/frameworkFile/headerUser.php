        <head>
            <link rel="stylesheet" href="/esercizioFinaleInformatica/frameworkFile/headerUser.css">
        </head>

        <?php 
             $current_page = basename($_SERVER['PHP_SELF']); 
        ?>

        <div class="intestazioneUser">
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

            <div class="pulsantiUtente">
                <a href="/esercizioFinaleInformatica/loginFile/loginForm.php" class="login">Accedi</a>
                <a href="/esercizioFinaleInformatica/signupFile/signupForm.php" class="signup">Registrati</a>
          </div>
        </div>
