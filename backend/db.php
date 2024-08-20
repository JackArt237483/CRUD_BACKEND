<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/identifier.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo 'Ошибка: ' . $e->getMessage();
}
?>
