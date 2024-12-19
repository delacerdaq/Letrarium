<?php
include_once '../config/userDAO.php';

class UserController {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    public function registerUser($username, $name, $email, $password, $terms) {
        $userDAO = new UserDAO(); 
        return $userDAO->register($username, $name, $email, $password, $terms);
    }
    
}
?>
