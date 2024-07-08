<?php
include_once '../config/Database.php';
include_once '../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function createUser($name, $email, $password) {
        $this->user->name = $name;
        $this->user->email = $email;
        $this->user->password = $password;

        if($this->user->create()) {
            return json_encode(array("message" => "Usuário cadastrado."));
        } else {
            return json_encode(array("message" => "Não foi possível cadastrar usuário."));
        }
    }
}
?>
