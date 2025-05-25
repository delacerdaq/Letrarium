const loadingOverlay = document.getElementById('loading-overlay');
if (loadingOverlay) {
    loadingOverlay.style.display = 'flex';
}

const MINIMUM_LOADING_TIME = 500; 
let loadingStartTime = Date.now();

function hideLoading() {
    const currentTime = Date.now();
    const elapsedTime = currentTime - loadingStartTime;
    
    if (elapsedTime < MINIMUM_LOADING_TIME) {
        setTimeout(() => {
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        }, MINIMUM_LOADING_TIME - elapsedTime);
    } else {        
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    }
}

function showLoading() {
    loadingStartTime = Date.now();
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';
    }
}

document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && !link.hasAttribute('data-no-loading')) {
        e.preventDefault(); 
        showLoading();
        
        setTimeout(() => {
            window.location.href = link.href;
        }, MINIMUM_LOADING_TIME);
    }
});

document.addEventListener('submit', function(e) {
    if (!e.target.hasAttribute('data-no-loading')) {
        e.preventDefault(); 
        showLoading();
        
        setTimeout(() => {
            e.target.submit();
        }, MINIMUM_LOADING_TIME);
    }
});

window.addEventListener('popstate', function() {
    showLoading();
});

window.addEventListener('beforeunload', function() {
    showLoading();
});

window.addEventListener('load', hideLoading); 