<?php
include_once __DIR__ . '/../dao/IDao.php';
include_once __DIR__ . '/../classe/Position.php';
include_once __DIR__ . '/../connexion/Connexion.php';

class PositionService implements IDao {
    private $connexion;

    public function __construct() {
        $this->connexion = new Connexion();
    }

    public function create($position) {
        $sql  = "INSERT INTO `position`(latitude, longitude, date_position, imei)
                 VALUES(:latitude, :longitude, :date_position, :imei)";
        $stmt = $this->connexion->getConnexion()->prepare($sql);
        $stmt->execute([
            ':latitude'      => $position->getLatitude(),
            ':longitude'     => $position->getLongitude(),
            ':date_position' => $position->getDatePosition(),
            ':imei'          => $position->getImei(),
        ]);
        return $this->connexion->getConnexion()->lastInsertId();
    }

    public function getAll() {
        $sql  = "SELECT * FROM `position` ORDER BY date_position DESC";
        $stmt = $this->connexion->getConnexion()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql  = "SELECT * FROM `position` WHERE id = :id";
        $stmt = $this->connexion->getConnexion()->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($obj) { /* non utilisé dans ce TP */ }
    public function delete($obj) { /* non utilisé dans ce TP */ }
}
?>
