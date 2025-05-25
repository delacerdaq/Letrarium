<?php
class Loading {
    public static function render() {
        return '
        <style>
            #loading-overlay {
                position: fixed;
                inset: 0;
                background-color: rgba(255, 255, 255, 0.9);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .loading-spinner {
                display: inline-block;
                width: 64px;
                height: 64px;
                border: 4px solid #9333ea;
                border-top: 4px solid transparent;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
        <div id="loading-overlay">
            <div class="text-center">
                <div class="loading-spinner"></div>
                <p class="mt-4 text-purple-700 font-semibold">Carregando...</p>
            </div>
        </div>';
    }
}
?> 