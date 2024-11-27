function toggleCommentsPopup(poemId) {
    const popup = document.getElementById('comments-popup-' + poemId);
    if (popup.style.display === 'none' || popup.style.display === '') {
        popup.style.display = 'block';
        loadComments(poemId); // Carrega os comentários somente quando abrir
    } else {
        popup.style.display = 'none';
    }
}
