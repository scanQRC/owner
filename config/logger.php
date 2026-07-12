<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

/*
|--------------------------------------------------------------------------
| Audit Logger
|--------------------------------------------------------------------------
|
| Writes activity into audit_logs table.
|
*/

function audit_log(
    ?int $adminId,
    string $action,
    string $module,
    ?int $recordId = null,
    array $details = []
): void {

    global $pdo;

    try {

        $sql = "
            INSERT INTO audit_logs
            (
                admin_id,
                action,
                module,
                record_id,
                ip_address,
                user_agent,
                details,
                created_at
            )
            VALUES
            (
                :admin_id,
                :action,
                :module,
                :record_id,
                :ip_address,
                :user_agent,
                :details,
                NOW()
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([

            ':admin_id'  => $adminId,
            ':action'    => $action,
            ':module'    => $module,
            ':record_id' => $recordId,
            ':ip_address'=> $_SERVER['REMOTE_ADDR'] ?? '',
            ':user_agent'=> substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ':details'   => json_encode(
                $details,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),

        ]);

    } catch (Throwable $e) {

        // Never break the application if logging fails.
    }
}
