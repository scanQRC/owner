<?php

declare(strict_types=1);

session_start();

require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

if (empty($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Dashboard';

include_once '../includes/header.php';
?>

<div class="container py-4">

    <h2 class="mb-4">
        Welcome,
        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
    </h2>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Vehicles</h5>

                    <?php

                    $stmt = $pdo->prepare("
                        SELECT COUNT(*)
                        FROM vehicles
                        WHERE user_id = ?
                    ");

                    $stmt->execute([
                        $_SESSION['user_id']
                    ]);

                    ?>

                    <h2>
                        <?php echo (int)$stmt->fetchColumn(); ?>
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total QR Codes</h5>

                    <?php

                    $stmt = $pdo->prepare("
                        SELECT COUNT(*)
                        FROM vehicles
                        WHERE user_id = ?
                        AND qr_token IS NOT NULL
                    ");

                    $stmt->execute([
                        $_SESSION['user_id']
                    ]);

                    ?>

                    <h2>
                        <?php echo (int)$stmt->fetchColumn(); ?>
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Scans</h5>

                    <?php

                    $stmt = $pdo->prepare("
                        SELECT COUNT(*)
                        FROM scan_logs sl
                        INNER JOIN vehicles v
                        ON v.id = sl.vehicle_id
                        WHERE v.user_id = ?
                    ");

                    $stmt->execute([
                        $_SESSION['user_id']
                    ]);

                    ?>

                    <h2>
                        <?php echo (int)$stmt->fetchColumn(); ?>
                    </h2>

                </div>
            </div>
        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
