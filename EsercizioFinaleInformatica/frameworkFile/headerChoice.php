<?php
if (session_status() === PHP_SESSION_NONE) session_start(); 

$json = file_get_contents(__DIR__ . '/file.json');
$obj = json_decode($json); 
$pageName = basename($_SERVER['PHP_SELF']);

if(in_array($pageName, $obj->DBPages)){
    require_once __DIR__ . '/dbHandler.php';
}

if(in_array($pageName, $obj->noHeaderFooterPages)){
    return; 
}

$ruolo = isset($_SESSION['ruolo']) ? $_SESSION['ruolo'] : null;
 
if($ruolo === 'admin') {
    require_once __DIR__ . '/headerAdmin.php';
} elseif($ruolo === 'proprietario') {
    require_once __DIR__ . '/headerProprietario.php';
} else {
    require_once __DIR__ . '/headerUser.php';
}
 
register_shutdown_function(function() { //quando e stato caricato tutto
    require_once __DIR__ . '/footer.php';
});
?>