<?php
header('Content-Type: application/json');

    global $db;
    include 'db.php';

    $id = $_GET['id'] ?? null;

    if ($id) {
        try {
            $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['deleted' => $stmt->rowCount()]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Missing id']);
    }
?>