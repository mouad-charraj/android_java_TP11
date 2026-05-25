<?php
/**
 * Script d'initialisation de la base de données.
 * Accéder à : http://localhost/localisation/init_db.php
 * Ce script crée la base et la table si elles n'existent pas encore.
 */

$host     = 'localhost';
$login    = 'root';
$password = '';

try {
    // Connexion sans spécifier de base pour pouvoir créer la base
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $login, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS localisation
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci");

    // Utiliser la base
    $pdo->exec("USE localisation");

    // Créer la table position
    $pdo->exec("CREATE TABLE IF NOT EXISTS position (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        latitude      DOUBLE      NOT NULL,
        longitude     DOUBLE      NOT NULL,
        date_position DATETIME    NOT NULL,
        imei          VARCHAR(50) NOT NULL
    )");

    $success = "✅ Base de données 'localisation' et table 'position' créées avec succès !";
} catch (Exception $e) {
    $error = "❌ Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Init DB — Localisation</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f0f1a;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: #1a1a2e;
            border: 1px solid #00cec9;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 0 40px rgba(0,206,201,0.15);
        }
        h1 { color: #00cec9; font-size: 1.8rem; margin-bottom: 20px; }
        .success { color: #00b894; font-size: 1.1rem; padding: 20px; background: rgba(0,184,148,0.1); border-radius: 8px; }
        .error   { color: #d63031; font-size: 1.1rem; padding: 20px; background: rgba(214,48,49,0.1); border-radius: 8px; }
        a { display: inline-block; margin-top: 24px; padding: 12px 24px; background: #00cec9; color: #0f0f1a; border-radius: 8px; text-decoration: none; font-weight: bold; }
        a:hover { background: #00b5b2; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🗄️ Initialisation DB</h1>
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <a href="index.php">→ Voir le Dashboard</a>
    </div>
</body>
</html>
