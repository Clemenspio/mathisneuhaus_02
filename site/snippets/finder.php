<?php
// site/snippets/finder.php
// Saubere Trennung der Finder-Struktur
?>

<!-- Background Image -->
<div class="background-image" id="backgroundImage"></div>

<!-- About Overlay -->
<div class="about-overlay" id="aboutOverlay" style="display: none;">
    <div class="about-text" id="aboutText">
        <div class="loading">
            <div class="spinner"></div>
            Loading about information...
        </div>
    </div>
</div>

<!-- Finder Interface -->
<div class="finder-interface">
    <div class="finder-container" id="finderContainer">
        <!-- Hover Background within Container -->
        <div class="finder-hover-bg" id="finderHoverBg"></div>
        
        <!-- Image Overlay -->
        <div class="image-overlay" id="imageOverlay" onclick="hideImageOverlay()" style="display: none;">
            <div class="image-container" onclick="event.stopPropagation()">
                <img id="overlayImage" src="" alt="">
            </div>
        </div>

        <!-- Text File Overlay -->
        <div class="text-overlay" id="textOverlay" onclick="hideTextOverlay()" style="display: none;">
            <div class="text-container" onclick="event.stopPropagation()">
                <!-- Close Button -->
                <button class="text-close-btn" onclick="hideTextOverlay()" title="Schließen">&times;</button>
                
                <!-- Scrollbarer Inhalt -->
                <div class="text-content" id="textContent">
                    <div class="text-loading">
                        <div class="text-spinner"></div>
                        Text wird geladen...
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="finder-header">
            <svg class="user-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            <span class="site-title" id="siteTitle" onclick="toggleAboutPage()">Mathis Neuhaus</span>
        </div>
        
        <!-- Main Content -->
        <div class="finder-main">
            <div class="finder-columns" id="finderColumns">
                <!-- Columns will be dynamically added here -->
            </div>
        </div>
    </div>
</div>