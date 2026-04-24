<?php
class DBHandler {
    private static $pdo; 

    private function __construct() {} // Impedisce l'istanza diretta della classe (singleton)

    public static function getPDO(){
        if(self::$pdo == null){ 
            self::connect_database(); 
        }
        return self::$pdo;        // se ce gia ritorna quella che c gia, altrimenti la crea e poi la ritorna
    }

    private static function connect_database() {
        define('USER', 'root');
        define('PASSWORD', ''); 

        try {
            $connection_string = 'mysql:host=localhost;dbname=futsalhouse;charset=utf8';
            
            $connection_array = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            self::$pdo = new PDO($connection_string, USER, PASSWORD, $connection_array); //crea connessione

        } catch(PDOException $e) {
            die("Errore connessione: " . $e->getMessage());
        }
    }   
}
?>