window.onload = function() {
    // Requisição para buscar poemas curtidos
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../view/toggle_like.php?get_likes=true", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                const likedPoems = response.liked_poems || [];

                likedPoems.forEach(poemId => {
                    let heartSvg = document.getElementById('heart-svg-' + poemId);
                    if (heartSvg) {
                        heartSvg.style.stroke = 'red';
                    }
                });
            } catch (e) {
                console.error("Erro ao carregar curtidas:", e);
            }
        }
    };
    xhr.send();
};

function toggleHeart(poemId) {
    let heartSvg = document.getElementById('heart-svg-' + poemId);
    let isLiked = likedPoems[poemId] || false;

    // Alterna a classe "liked" com base no estado
    if (isLiked) {
        heartSvg.classList.remove('liked');
        likedPoems[poemId] = false;
        sendLikeData(poemId, false);
    } else {
        heartSvg.classList.add('liked');
        likedPoems[poemId] = true;
        sendLikeData(poemId, true);
    }
}

function sendLikeData(poemId, liked) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../view/toggle_like.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                console.log(response); // Para debugar a resposta do servidor
            } catch (e) {
                console.error("Erro ao analisar JSON:", e);
            }
        }
    };

    xhr.send("poem_id=" + poemId + "&liked=" + liked);
}
