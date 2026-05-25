<?php
class Connexion {
    private $connexion;

    public function __construct() {
        $host     = 'localhost';
        $dbname   = 'localisation';
        $login    = 'root';
        $password = '';

        try {
            $this->connexion = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $login,
                $password
            );
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die(json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]));
        }
    }

    public function getConnexion() {
        return $this->connexion;
    }
}
?>
