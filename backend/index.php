<?php
global $db;
header('Content-Type: application/json');
include 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

try {
    switch ($method) {
        case 'POST':
            if (isset($input['name'], $input['quantity'], $input['year'], $input['description'])) {
                $stmt = $db->prepare('INSERT INTO products (name, quantity, year, description) VALUES (?, ?, ?, ?)');
                $stmt->execute([$input['name'], $input['quantity'], $input['year'], $input['description']]);
                echo json_encode(['id' => $db->lastInsertId()]);
            } else {
                echo json_encode(['error' => 'Invalid input data']);
            }
            break;

        case 'GET':
            if ($id) {
                $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
                $stmt->execute([$id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $db->query('SELECT * FROM products');
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            echo json_encode($result);
            break;

        case 'PUT':
            if ($id && isset($input['name'], $input['quantity'], $input['year'], $input['description'])) {
                $stmt = $db->prepare('UPDATE products SET name = ?, quantity = ?, year = ?, description = ? WHERE id = ?');
                $stmt->execute([$input['name'], $input['quantity'], $input['year'], $input['description'], $id]);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Invalid input data or missing id']);
            }
            break;

        case 'DELETE':
            if ($id) {
                $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
                $stmt->execute([$id]);
                echo json_encode(['deleted' => $stmt->rowCount()]);
            } else {
                echo json_encode(['error' => 'Missing id']);
            }
            break;

        default:
            echo json_encode(['error' => 'Unsupported request method']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
