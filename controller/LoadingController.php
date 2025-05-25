<?php
require_once '../view/components/loading.php';

class LoadingController {
    private static $instance = null;
    private $isLoading = true;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function startLoading() {
        $this->isLoading = true;
        echo Loading::render();
    }

    public function stopLoading() {
        $this->isLoading = false;
    }

    public function isLoading() {
        return $this->isLoading;
    }
}
?> 