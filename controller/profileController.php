<?php
require_once '../model/Profile.php';

class ProfileController {

    // Get profile by user ID
    public function getProfileByUserId($userId) {
        return Profile::fetchProfileByUserId($userId);
    }

    // Update entire profile (bio and picture)
    public function updateProfile($userId, $data) {
        $profile = new Profile($userId);
        return $profile->updateProfile($userId, $data);
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
        $profile = new Profile($userId);
        return $profile->updateProfilePicture($userId, $profilePicture);
    }

    // Update bio
    public function updateBio($userId, $bio) {
        $profile = new Profile($userId);
        return $profile->updateBio($userId, $bio);
    }

    // Create profile if not exists
    public function createProfile($userId, $profilePicture = null, $bio = null) {
        $profile = new Profile($userId, $profilePicture, $bio);
        return $profile->createProfile($userId, $profilePicture, $bio);
    }
}
?>
