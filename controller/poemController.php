<?php
require_once '../config/PoemDAO.php';
require_once '../config/TagDAO.php';
require_once '../config/LikeDAO.php';

class PoemController {

    private $poemDAO;
    private $tagDAO;
    private $likeDAO;

    public function __construct() {
        $this->poemDAO = new PoemDAO();
        $this->tagDAO = new tagDAO();
        $this->likeDAO = new LikeDAO();
    }

    public function validatePoem(Poem $poem) {
        if (empty($poem->getTitle())) {
            return "O título não pode ser vazio.";
        }
        if (empty($poem->getContent())) {
            return "O conteúdo não pode ser vazio.";
        }
        return true;
    }

    public function savePoem($title, $content, $visibility, $authorId, $categoryId, $tags) {
        $poem = new Poem($title, $content, $visibility, $authorId, $categoryId);
        $validationResult = $this->validatePoem($poem);

        if ($validationResult !== true) {
            return $validationResult;
        }

        // Salva o poema via PoemDAO
        if ($this->poemDAO->save($poem)) {
            $poemId = $poem->getId();

            foreach (explode(',', $tags) as $tag) {
                $this->tagDAO->addTag($poemId, trim($tag));
            }

            return "Poema salvo com sucesso.";
        } else {
            return "Erro ao salvar o poema.";
        }
    }

    public function editPoem($poemId, $data) {
        $poem = $this->poemDAO->getById($poemId);
        if ($poem) {
            $title = $data['title'];
            $content = $data['content'];
            $categoryId = $data['category_id'];
            $visibility = $data['visibility'];
            $tags = isset($data['tags']) ? $data['tags'] : [];

            $tagsArray = is_array($tags) 
                ? array_map('trim', $tags) 
                : array_map('trim', explode(',', $tags));

            if ($this->poemDAO->editPoem($poemId, $title, $content, $categoryId, $visibility)) {
                $this->tagDAO->editTags($poemId, $tagsArray);
                return "Poema editado com sucesso!";
            } else {
                return "Erro ao editar o poema.";
            }
        } else {
            return "Poema não encontrado.";
        }
    }

    public function deletePoem($poemId, $authorId) {
        return $this->poemDAO->deletePoem($poemId, $authorId);
    }

    public function getAllPoems() {
        return $this->poemDAO->getAll();
    }

    public function getPoemsByCategory($categoryId) {
        return $this->poemDAO->getByCategory($categoryId);
    }

    public function getPoemsByUser($userId) {
        return $this->poemDAO->getByUser($userId);
    }

    public function getAllPoemsWithTagsAndProfilePictures() {
        return $this->poemDAO->getAllPoemsWithTagsAndProfilePictures();
    }

    public function searchPoems($keyword) {
        return $this->poemDAO->search($keyword);
    }

    public function getPoemById($poemId) {
        $poem = $this->poemDAO->getById($poemId);
        if ($poem) {
            $tags = $this->tagDAO->getTags($poemId);
            $poem['tags'] = array_column($tags, 'name');
        }
        return $poem;
    }

    public function getCategories() {
        return $this->poemDAO->getCategories();
    }

    public function hasLiked($poemId, $userId) {
        return $this->likeDAO->hasLiked($poemId, $userId);
    }

    public function countLikes($poemId) {
        return $this->likeDAO->countLikes($poemId);
    }
}
?>
