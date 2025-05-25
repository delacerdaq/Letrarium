function toggleCommentsPopup(poemId) {
    const popup = document.getElementById('comments-popup-' + poemId);
    if (popup.style.display === 'none' || popup.style.display === '') {
        popup.style.display = 'block';
        loadComments(poemId); // Carrega os comentários somente quando abrir
    } else {
        popup.style.display = 'none';
    }
}

function loadComments(poemId) {
    const commentsList = document.getElementById('comments-list-' + poemId);
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../controller/get_comments.php?poem_id=' + poemId, true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const comments = JSON.parse(xhr.responseText);
                commentsList.innerHTML = ''; // Limpa a lista atual
                
                if (comments.length === 0) {
                    commentsList.innerHTML = '<p class="text-gray-500 text-center">Nenhum comentário ainda.</p>';
                    return;
                }

                comments.forEach(comment => {
                    const commentElement = document.createElement('div');
                    commentElement.className = 'bg-gray-50 p-4 rounded-lg';
                    
                    // Formata a data
                    const date = new Date(comment.created_at);
                    const formattedDate = date.toLocaleDateString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    commentElement.innerHTML = `
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                ${comment.profile_picture ? 
                                    `<img src="${comment.profile_picture}" alt="Profile" class="w-10 h-10 rounded-full object-cover">` :
                                    `<div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>`
                                }
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-800">${comment.name}</h4>
                                    <span class="text-sm text-gray-500">${formattedDate}</span>
                                </div>
                                <p class="mt-1 text-gray-600">${comment.content}</p>
                            </div>
                        </div>
                    `;
                    
                    commentsList.appendChild(commentElement);
                });
            } catch (e) {
                console.error('Erro ao processar comentários:', e);
                commentsList.innerHTML = '<p class="text-red-500 text-center">Erro ao carregar comentários.</p>';
            }
        }
    };
    
    xhr.send();
}

function submitComment(event, poemId) {
    event.preventDefault();
    
    const form = event.target;
    const textarea = form.querySelector('textarea[name="comment_text"]');
    const content = textarea.value.trim();
    
    if (!content) return;
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controller/add_comment.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    textarea.value = ''; // Limpa o campo de texto
                    loadComments(poemId); // Recarrega os comentários
                } else {
                    alert('Erro ao adicionar comentário: ' + response.message);
                }
            } catch (e) {
                console.error('Erro ao processar resposta:', e);
                alert('Erro ao adicionar comentário');
            }
        }
    };
    
    xhr.send('poem_id=' + poemId + '&content=' + encodeURIComponent(content));
}
