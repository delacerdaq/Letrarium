<?php
require_once '../model/Poem.php';
require_once '../model/Tag.php';
require_once '../config/database.php';

class PoemController {

    private $poem;

    public function __construct() {
        $db = new Database();
        $this->poem = new Poem($db->getConnection());
    }

    // Método para validar o poema, esperando um objeto Poem.
    public function validatePoem(Poem $poem) {
        // Validações de exemplo
        if (empty($poem->getTitle())) {
            return "O título não pode ser vazio.";
        }
        if (empty($poem->getContent())) {
            return "O conteúdo não pode ser vazio.";
        }
        // Outras validações podem ser adicionadas aqui
        return true; // Se passar todas as validações
    }

    // Método para salvar o poema, aceitando um objeto Poem diretamente.
    public function savePoem($title, $content, $visibility, $authorId, $categoryId, $tags) {
        $poem = new Poem($title, $content, $visibility, $authorId, $categoryId);
        $validationResult = $this->validatePoem($poem);

        if ($validationResult !== true) {
            return $validationResult;
        }

        // Salva o poema e retorna seu ID
        if ($poem->save()) {
            $poemId = $poem->getId(); // Supondo que o método getId() retorne o ID do poema salvo
            $tagModel = new Tag();

            // Adiciona cada tag ao poema
            foreach (explode(',', $tags) as $tag) {
                $tagModel->addTag($poemId, trim($tag));
            }

            return "Poema salvo com sucesso.";
        } else {
            return "Erro ao salvar o poema.";
        }
    }

    public function editPoem($poemId, $data) {
        if ($this->poem->getById($poemId)) {
            $title = $data['title'];
            $content = $data['content'];
            $categoryId = $data['category_id'];
            $visibility = $data['visibility'];
    
            // Verifica e manipula as tags
            $tags = isset($data['tags']) ? $data['tags'] : [];
            if (is_array($tags)) {
                // Se já é um array, apenas trim e re-converta para string
                $tagsString = implode(',', array_map('trim', $tags));
            } else {
                // Caso contrário, assume que é uma string
                $tagsString = $tags;
            }
            // Divide as tags em um array, garantindo que esteja no formato correto
            $tagsArray = array_map('trim', explode(',', $tagsString));
    
            // Edita o poema através do controlador
            if ($this->poem->editPoem($poemId, $title, $content, $categoryId, $visibility)) {
                $tagModel = new Tag();
                $tagModel->editTags($poemId, $tagsArray); // Passa o array de tags para o método editTags
                return "Poema editado com sucesso!";
            } else {
                return "Erro ao editar o poema.";
            }
        } else {
            return "Poema não encontrado.";
        }
    }    

    // Método para excluir um poema
    public function deletePoem($poemId, $authorId) {
        return $this->poem->deletePoem($poemId, $authorId);
    }
    
    public function getAllPoems() {
        return Poem::getAll();
    }

    public function getPoemsByCategory($categoryId) {
        return Poem::getByCategory($categoryId);
    }

    public function getPoemsByUser($userId) {
        return Poem::getByUser($userId);
    }

    public function getAllPoemsWithTagsAndProfilePictures() {
        return Poem::getAllPoemsWithTagsAndProfilePictures();
    }

    /*
    public function getCategories() {
        return Poem::getCategories();
    }
    */

    public function searchPoems($keyword) {
        return Poem::search($keyword);
    }

    public function getPoemById($poemId) {
        $poem = $this->poem->getById($poemId);
        
        if ($poem) {
            $tagModel = new Tag();
            $tags = $tagModel->getTags($poemId);
            $poem['tags'] = array_column($tags, 'name'); // Agora isso deve funcionar corretamente
        }
        
        return $poem;
    }

    public function getCategories() {
        return $this->poem->getCategories();
    }
}
?>
