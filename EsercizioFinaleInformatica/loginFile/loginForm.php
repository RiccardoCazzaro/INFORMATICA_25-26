<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <!-- per occhio icona -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">  
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
      
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password:</label>
      <div class="password-field">
          <input type="password" id="password" name="password" required>
          <i class="fa-solid fa-eye" id="togglePassword"></i>
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
            setTimeout(() => segnaleErr.remove(), 800);
        }, 4000); 
    }
});
</script>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Passa da password a text e viceversa
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Cambia l'icona tra occhio normale e sbarrato
        this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>