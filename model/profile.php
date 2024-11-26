<?php
class Profile {
    private $id;
    private $userId;
    private $profilePicture;
    private $bio;

    public function __construct($userId, $profilePicture = null, $bio = null) {
        $this->userId = $userId;
        $this->profilePicture = $profilePicture;
        $this->bio = $bio;
    }

    // Getters and Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getProfilePicture() {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;
    }

    public function getBio() {
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = $bio;
    }
}
?>
