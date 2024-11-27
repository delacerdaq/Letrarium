// Inicializando o objeto XMLHttpRequest
var xhr = new XMLHttpRequest();

// Configurando a requisição
xhr.open('POST', '../view/toggle_like.php', true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

xhr.onload = function() {
    try {
        var response = JSON.parse(xhr.responseText);
        // Process your response
    } catch (error) {
        console.error("Error parsing response:", xhr.responseText);
    }
};

function toggleLike(poemId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../view/toggle_like.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onload = function() {
        if (xhr.status == 200) {
            try {
                const response = JSON.parse(xhr.responseText);  // Tenta fazer o parse do JSON
                // Processar a resposta aqui
                if (response.success) {
                    console.log("Curtida atualizada");
                }
            } catch (e) {
                console.error("Erro ao analisar JSON:", e);
                console.log("Resposta do servidor:", xhr.responseText);  // Verifique o que foi retornado
            }
        }
    };
    
    xhr.send("poemId=" + poemId);
}

function toggleDislike(poemId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../view/toggle_like.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                var likeIcon = document.getElementById('thumb-up-' + poemId);
                var dislikeIcon = document.getElementById('thumb-down-' + poemId);

                // Atualiza as cores dos ícones
                if (response.liked) {
                    likeIcon.style.fill = 'green';
                    dislikeIcon.style.fill = 'black';
                } else {
                    likeIcon.style.fill = 'black';
                    dislikeIcon.style.fill = 'red';
                }
            }
        }
    };
    xhr.send('poem_id=' + poemId);
}
