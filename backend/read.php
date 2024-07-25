<?php
header('Content-Type: application/json');

global $db;
    include_once "db.php";
        // получение id в get запросе
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $db->query('SELECT * FROM products');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($result);
    ?>