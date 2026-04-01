<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$json = file_get_contents(__DIR__ . '/file.json');
$obj = json_decode($json); 
$pageName = basename($_SERVER['PHP_SELF']);

if(isset($obj->DBPages) && in_array($pageName, $obj->DBPages)){
    require_once __DIR__ . '/dbHandler.php';
}

if(in_array($pageName, $obj->noHeaderFooterPages)){
    return; 
}

if(isset($_SESSION['idUtente'])) {
    require_once __DIR__ . '/headerAdmin.php';
} else {
    require_once __DIR__ . '/headerUser.php';
}

register_shutdown_function(function() {
    require_once __DIR__ . '/footer.php';
});
?>