<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once __DIR__ . '/service/PositionService.php';
    create();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.']);
}

function create() {
    $latitude     = isset($_POST['latitude'])     ? $_POST['latitude']     : null;
    $longitude    = isset($_POST['longitude'])    ? $_POST['longitude']    : null;
    $datePosition = isset($_POST['date_position']) ? $_POST['date_position'] : date('Y-m-d H:i:s');
    $imei         = isset($_POST['imei'])         ? $_POST['imei']         : 'unknown';

    if ($latitude === null || $longitude === null) {
        http_response_code(400);
        echo json_encode(['error' => 'latitude et longitude sont obligatoires']);
        return;
    }

    try {
        $service  = new PositionService();
        $position = new Position(null, $latitude, $longitude, $datePosition, $imei);
        $newId    = $service->create($position);

        echo json_encode([
            'success' => true,
            'message' => 'Position enregistrée avec succès',
            'id'      => $newId,
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
