<?php
require_once '../model/Profile.php';

class ProfileController {

    // Get profile by user ID
    public function getProfileByUserId($userId) {
        return Profile::fetchProfileByUserId($userId);
    }

    // Update profile
    public function updateProfile($userId, $data) {
        $profile = new Profile($userId);
        return $profile->updateProfile($userId, $data);
    }

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

    // Update profile picture
    public function updateProfilePicture($userId, $profilePicture) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "UPDATE profile SET profile_picture = :profile_picture WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':profile_picture', $profilePicture);
        $stmt->bindValue(':user_id', $userId);

        return $stmt->execute();
    }

    // Update bio
    public function updateBio($userId, $bio) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "UPDATE profile SET bio = :bio WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':user_id', $userId);

        return $stmt->execute();
    }

    // Cria uma nova entrada de perfil se ela não existir
    public function createProfile($userId, $profilePicture = null, $bio = null) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "INSERT INTO profile (user_id, profile_picture, bio) VALUES (:user_id, :profile_picture, :bio)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':profile_picture', $profilePicture);
        $stmt->bindValue(':bio', $bio);

        return $stmt->execute();
    }
}
?>
