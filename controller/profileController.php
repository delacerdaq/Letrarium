<?php
require_once '../config/ProfileDAO.php';

class ProfileController {

    private $profileDAO;

    public function __construct() {
        $this->profileDAO = new ProfileDAO();
    }

    // Get profile by user ID
    public function getProfileByUserId($userId) {
        return $this->profileDAO->fetchProfileByUserId($userId);
    }

    // Update entire profile (bio and picture)
    public function updateProfile($userId, $data) {
        return $this->profileDAO->updateProfile($userId, $data);
    }

    // Upload photo and update profile picture
    public function uploadPhoto($userId, $photo) {
        $targetDir = "../uploads/profile_pictures/";
        $targetFile = $targetDir . basename($photo['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Verifica se o arquivo é uma imagem
        $check = getimagesize($photo['tmp_name']);
        if ($check === false) {
            return "O arquivo não é uma imagem.";
        }

        // Verifica o tamanho do arquivo
        if ($photo['size'] > 500000) { // 500 KB
            return "O arquivo é muito grande.";
        }

        // Permite apenas certos formatos de arquivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            return "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
        }

        // Faz o upload do arquivo
        if (move_uploaded_file($photo['tmp_name'], $targetFile)) {
            // Atualiza o caminho da foto no perfil do usuário
            if ($this->updateProfilePicture($userId, $targetFile)) {
                return true;
            } else {
                return "Erro ao atualizar o caminho da foto no perfil.";
            }
        } else {
            return "Erro ao fazer upload do arquivo.";
        }
    }

    // Update profile picture in the profile table
    public function updateProfilePicture($userId, $profilePicture) {
        return $this->profileDAO->updateProfilePicture($userId, $profilePicture);
    }

    // Update bio
    public function updateBio($userId, $bio) {
        return $this->profileDAO->updateBio($userId, $bio);
    }

    // Create profile if not exists
    public function createProfile($userId, $profilePicture = null, $bio = null) {
        return $this->profileDAO->createProfile($userId, $profilePicture, $bio);
    }
}
?>
