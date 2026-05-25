<?php
// Dashboard — liste toutes les positions enregistrées
$positions = [];
$dbError   = '';

try {
    include_once __DIR__ . '/service/PositionService.php';
    $service   = new PositionService();
    $positions = $service->getAll();
} catch (Exception $e) {
    $dbError = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Localisation — MouadLocalizer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Reset & Base ───────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-deep:    #0a0a14;
            --bg-card:    #13131f;
            --bg-row:     #1a1a2e;
            --accent:     #00cec9;
            --accent2:    #6c5ce7;
            --text:       #e8e8f0;
            --text-muted: #8888aa;
            --border:     rgba(0,206,201,0.2);
            --danger:     #d63031;
            --success:    #00b894;
            --radius:     12px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Header ─────────────────────────────────────────────── */
        header {
            background: linear-gradient(135deg, #13131f 0%, #1a1a2e 100%);
            border-bottom: 1px solid var(--border);
            padding: 20px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .brand-name { font-size: 1.3rem; font-weight: 700; color: var(--accent); }
        .brand-sub  { font-size: 0.75rem; color: var(--text-muted); }

        .header-badge {
            background: rgba(0,206,201,0.1);
            border: 1px solid var(--accent);
            color: var(--accent);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* ── Main Layout ─────────────────────────────────────────── */
        main { padding: 32px; max-width: 1200px; margin: 0 auto; }

        /* ── Stats Row ───────────────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,206,201,0.15);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
        }
        .stat-label { font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .stat-value { font-size: 2rem; font-weight: 700; color: var(--accent); }
        .stat-icon  { position: absolute; right: 20px; top: 20px; font-size: 2rem; opacity: 0.15; }

        /* ── Table Section ───────────────────────────────────────── */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title span { color: var(--accent); }

        .table-wrapper {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }

        thead tr {
            background: linear-gradient(90deg, rgba(0,206,201,0.08), rgba(108,92,231,0.08));
            border-bottom: 1px solid var(--border);
        }
        th {
            padding: 14px 20px;
            text-align: left;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(0,206,201,0.04); }

        td {
            padding: 14px 20px;
            font-size: 0.9rem;
            color: var(--text);
        }
        td.id-cell {
            color: var(--accent2);
            font-weight: 700;
            font-size: 0.85rem;
        }
        td.coord {
            font-family: 'Courier New', monospace;
            color: var(--accent);
            font-size: 0.88rem;
        }
        td.imei-cell {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-family: monospace;
        }
        td.date-cell {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .badge-link {
            display: inline-flex; align-items: center; gap: 4px;
            background: rgba(0,206,201,0.1);
            color: var(--accent);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        .badge-link:hover { background: rgba(0,206,201,0.2); }

        /* ── Empty / Error States ────────────────────────────────── */
        .empty-state, .error-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-state .emoji, .error-state .emoji { font-size: 3rem; margin-bottom: 12px; }
        .error-state p { color: var(--danger); }

        /* ── Refresh button ──────────────────────────────────────── */
        .toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }
        .btn-refresh {
            display: flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #0a0a14;
            border: none; border-radius: 8px;
            padding: 10px 20px; font-size: 0.9rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-refresh:hover { opacity: 0.9; transform: translateY(-1px); }

        /* ── Footer ──────────────────────────────────────────────── */
        footer {
            text-align: center;
            padding: 24px;
            color: var(--text-muted);
            font-size: 0.8rem;
            border-top: 1px solid var(--border);
            margin-top: 40px;
        }
        footer strong { color: var(--accent); }
    </style>
</head>
<body>

<header>
    <div class="brand">
        <div class="brand-icon">📍</div>
        <div>
            <div class="brand-name">MoudLocalizer</div>
            <div class="brand-sub">Tableau de bord — Positions GPS</div>
        </div>
    </div>
    <div class="header-badge">🟢 Serveur actif</div>
</header>

<main>

    <?php if ($dbError): ?>
    <div class="error-state">
        <div class="emoji">❌</div>
        <p><strong>Erreur de base de données :</strong> <?= htmlspecialchars($dbError) ?></p>
        <p style="margin-top:12px;">
            <a href="init_db.php" style="color:var(--accent)">→ Initialiser la base de données</a>
        </p>
    </div>
    <?php else: ?>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total positions</div>
            <div class="stat-value"><?= count($positions) ?></div>
            <div class="stat-icon">📍</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Appareils uniques</div>
            <div class="stat-value"><?= count(array_unique(array_column($positions, 'imei'))) ?></div>
            <div class="stat-icon">📱</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Dernière mise à jour</div>
            <div class="stat-value" style="font-size:1rem; padding-top:8px;">
                <?= $positions ? htmlspecialchars($positions[0]['date_position']) : '—' ?>
            </div>
            <div class="stat-icon">🕐</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Endpoint API</div>
            <div class="stat-value" style="font-size:0.7rem; color:var(--text-muted); padding-top:8px; word-break:break-all;">
                /localisation/createPosition.php
            </div>
            <div class="stat-icon">🔌</div>
        </div>
    </div>

    <!-- Table -->
    <div class="section-title">
        <span>📋</span> Historique des positions enregistrées
    </div>

    <div class="toolbar">
        <a href="index.php" class="btn-refresh">🔄 Actualiser</a>
    </div>

    <div class="table-wrapper">
        <?php if (empty($positions)): ?>
        <div class="empty-state">
            <div class="emoji">🛰️</div>
            <p>Aucune position enregistrée pour l'instant.</p>
            <p style="margin-top:8px; font-size:0.85rem;">Lance l'application Android pour commencer à collecter des données.</p>
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Date &amp; Heure</th>
                    <th>IMEI / Appareil</th>
                    <th>Carte</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($positions as $pos): ?>
                <tr>
                    <td class="id-cell">#<?= htmlspecialchars($pos['id']) ?></td>
                    <td class="coord"><?= htmlspecialchars($pos['latitude']) ?></td>
                    <td class="coord"><?= htmlspecialchars($pos['longitude']) ?></td>
                    <td class="date-cell"><?= htmlspecialchars($pos['date_position']) ?></td>
                    <td class="imei-cell"><?= htmlspecialchars($pos['imei']) ?></td>
                    <td>
                        <a class="badge-link"
                           href="https://www.google.com/maps?q=<?= urlencode($pos['latitude']) ?>,<?= urlencode($pos['longitude']) ?>"
                           target="_blank">
                            🗺️ Maps
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <?php endif; ?>
</main>

<footer>
    Réalisé par <strong>CHARRAJ Mouad</strong> aka ZERO-XR7 &mdash; TP Géolocalisation Android &amp; PHP
</footer>

</body>
</html>
