<?php
class Tag {

    private $id;
    private $name;

    // Construtor para inicializar os atributos
    public function __construct($name = null) {
        $this->name = $name;
    }

    // Getters e Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}

?>
