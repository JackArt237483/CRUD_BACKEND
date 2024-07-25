<?php
    header('Content-Type: application/json');

    global $db;
    include 'db.php';

    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['name'], $input['quantity'], $input['year'], $input['description'])) {
        try {
            $stmt = $db->prepare('INSERT INTO products (name, quantity, year, description) VALUES (?, ?, ?, ?)');
            $stmt->execute([$input['name'], $input['quantity'], $input['year'], $input['description']]);
            echo json_encode(['id' => $db->lastInsertId()]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid input data']);
    }
?>
