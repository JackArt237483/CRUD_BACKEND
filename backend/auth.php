
<?php
/*password_hash-СОЗАДЕТ НОВЫЙ ХЕШ ПАРРОЛЯ
PASSWORD_BCRYPT генернирует новый хеш*/
include "db.php";
    global $db;
    class DataBase {
        private $db;
        public function _construct ($db){
            $this->db = $db;
        }
        //метод регистрации нового польщзователя
        public function register($username,$phone,$email,$password){
            $stmt = $this->db->prepare('INSERT INTO users($username,$phone,$email,$password) VALUES (?,?,?,?)');
            $stmt->execute([$username,$phone,$email,password_hash($password,PASSWORD_BCRYPT)]);
            return ['success' => true];
        }
        // проверка логина
        public function login($email,$password){
            $stmt = $this->db->prapare("SELECT * FROM users WHERE email = ?");
            $stmt->excute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user[$password])){
                return ['success' => true , 'user' => $user];
            } else {
                return ['error' => ' Здесь ошибка новая по осторожней'];
            }
        }
    }
?>
