<?php
    global $db;
    include "db.php";
    //Подключение к классам что у нас есть
    include "auth.php";
    //Подключение к базе даныы
    $connection = new DataBase($db);
    //Большая проверка был ли POST запрос
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Полученияя данных и превращение это все массив PHP
        $input = json_decode(file_get_contents('php://input'),true);
        // Проверка есть ли в получение данных один пункт хотя бы
        if(isset($input['username'],$input['phone'],$input['email'],$input['password'])){
            // Проверка произошла ли у нас регситраци со всеми данными и так далее
            $result = $connection->register($input['username'],$input['phone'],$input['email'],$input['password']);
            if($result['success']){
                $_SESSION['user'] = [
                    'username' => $input['username'],
                    'email' => $input['email']
                ];
                echo json_encode(['success' => 'Регистрация успешна']);
            } else {
                echo json_encode(['error' => 'Ошибка регистрации']);
            }
        }
    } elseif (isset($input['loginEmail'],$input['loginPassword'])){
        // Проверка логина и регистрации
        $result = $connection->login($input['loginEmail'],$input['loginPassword']);
        if($result['success']){
            $_SESSION['user'] = [
                'username' => $input['username'],
                'email' => $input['email']
            ];
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Неправавильный логин и пароль']);
        }
    } else {
        echo json_encode(['error' => 'Не все заполененно']);
    }
    exit();
?>
