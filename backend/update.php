<?php
    header('Content-Type: application/json');

    global $db;
    include_once "db.php";
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['id']) && isset($input['name'], $input['quantity'], $input['year'], $input['description'])) {
        $id = $_GET['id'];
        try {
            $stmt = $db->prepare('UPDATE products SET name = ?, quantity = ?, year = ?, description = ? WHERE id = ?');
            $stmt->execute([$input['name'], $input['quantity'], $input['year'], $input['description'], $id]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid input data or missing id']);
    }
    global $db;
    include 'db.php'; // Подключение к базе данных

?>
