<?php
class User {
    private $username;
    private $name;
    private $email;
    private $password;
    private $terms;

    public function __construct($username, $name, $email, $password, $terms) {
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->terms = $terms;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getTerms() {
        return $this->terms;
    }
}
?>
