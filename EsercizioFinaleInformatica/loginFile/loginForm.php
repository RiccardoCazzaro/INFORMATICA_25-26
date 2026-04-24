
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> <!-- icona occhio -->
  <link rel="stylesheet" href="loginForm.css?v=<?php echo time(); ?>">
  <title>Login - Futsal World</title>
</head>
<body>
  
  <div class="login-container">
    <?php if(isset($_GET['errore'])){ ?>
      <div id="erroreLogin" >
      <strong>Email o Password errati!</strong>
      </div>
    <?php
     } ?>
     

    <form action="login.php" method="POST">
      <h2>Accesso Utente</h2>
      <h4>Se non hai un account, <a href="../signupFile/signupForm.php">registrati qui</a></h4>
      
      
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password:</label>
      <div class="passwordCasella">
          <input type="password" id="password" name="password" required>
          <i class="fa-solid fa-eye" id="eyePassword"></i>
      </div>

      <input type="submit" value="Accedi">
      <div style="margin-top: 15px; text-align: center;">
        <a href="../home.php">Torna alla Home</a>
      </div>
    </form> 
  </div>

<script>
document.addEventListener("DOMContentLoaded", function() {  //dopo che la pagina è caricata
    const segnaleErr = document.getElementById("erroreLogin");
    if (segnaleErr) {
        setTimeout(() => { 
            segnaleErr.style.transition = "opacity 0.8s";
            segnaleErr.style.opacity = "0";
            setTimeout(() => segnaleErr.remove(), 800); //dopo 0.8 s scompare
        }, 4000 //dopo 4 secondi inizia a scomparire
        ); 
    }
});
</script>

  <script>
    const eyePassword = document.querySelector('#eyePassword');
    const password = document.querySelector('#password');

    eyePassword.addEventListener('click', function () {
        // Passa da password a text e viceversa
    let type;
    if (password.getAttribute('type') === 'password') {   //controlla vall e tipo
      type = 'text';
    } else {
      type = 'password';
   }      
   password.setAttribute('type', type); 
   this.classList.toggle('fa-eye');
   this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
