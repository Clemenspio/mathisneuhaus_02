<?php
// site/templates/default.php
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mathis Neuhaus</title>
    <link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
</head>
<body>
    <!-- Background Image -->
    <div class="background-image" id="backgroundImage"></div>
    
    <!-- About Overlay - Outside of finder -->
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

                        <!-- Text File Overlay - NEU HINZUGEFÜGT -->
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

    

    <script>
        // Global state
        let currentPath = '/';
        let columns = [];
        let contactData = null;
        let currentColumnIndex = 0;

                            // Initialize the finder immediately
                    console.log('Initializing finder...');
                    loadBackgroundImage();
                    loadRootContent();

        // Load random background image
        async function loadBackgroundImage() {
            try {
                const response = await fetch('/api/desktop-images');
                const data = await response.json();
                
                if (data.status === 'ok' && data.images && data.images.length > 0) {
                    // Select random image
                    const randomImage = data.images[Math.floor(Math.random() * data.images.length)];
                    const backgroundImage = document.getElementById('backgroundImage');
                    backgroundImage.style.backgroundImage = `url('${randomImage.url}')`;
                }
            } catch (error) {
                console.error('Failed to load background image:', error);
            }
        }

        // Load root content
        async function loadRootContent() {
            console.log('Loading root content...');
            try {
                const response = await fetch('/api/content');
                const data = await response.json();
                console.log('API response:', data);
                
                if (data.status === 'ok' && data.items && data.items.length > 0) {
                    console.log('Adding column with', data.items.length, 'items');
                    addColumn('Home', data.items);
                } else {
                    console.log('Using fallback data');
                    // Fallback data
                    addColumn('Home', [
                        { name: 'Journalismus', type: 'folder', path: '/journalismus', item_count: 2 },
                        { name: 'Kommunikation', type: 'folder', path: '/kommunikation', item_count: 1 },
                        { name: 'Kuration', type: 'folder', path: '/kuration', item_count: 0 },
                        { name: 'Redaktion', type: 'folder', path: '/redaktion', item_count: 0 },
                        { name: 'EN_Journalismus.docx', type: 'textfile', path: '/en-journalismus-docx' },
                        { name: 'DE_Journalismus.docx', type: 'textfile', path: '/de-journalismus-docx' }
                    ]);
                }
            } catch (error) {
                console.error('Failed to load content:', error);
                // Fallback data
                addColumn('Home', [
                    { name: 'Journalismus', type: 'folder', path: '/journalismus', item_count: 2 },
                    { name: 'Kommunikation', type: 'folder', path: '/kommunikation', item_count: 1 },
                    { name: 'Kuration', type: 'folder', path: '/kuration', item_count: 0 },
                    { name: 'Redaktion', type: 'folder', path: '/redaktion', item_count: 0 },
                    { name: 'EN_Journalismus.docx', type: 'textfile', path: '/en-journalismus-docx' },
                    { name: 'DE_Journalismus.docx', type: 'textfile', path: '/de-journalismus-docx' }
                ]);
            }
        }

        // Add a new column
        function addColumn(title, items, hoverImageUrl = null) {
            console.log('Adding column:', title, 'with', items.length, 'items');
            const columnsContainer = document.getElementById('finderColumns');
            
            const column = document.createElement('div');
            column.className = 'finder-column';
            
            // No header needed - minimal design
            
            const itemsList = document.createElement('div');
            itemsList.className = 'items-list';
            
            if (items.length === 0) {
                itemsList.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📁</div>
                        <p>Dieser Ordner ist leer</p>
                    </div>
                `;
            } else {
                            items.forEach(item => {
                const itemElement = createItemElement(item);
                itemsList.appendChild(itemElement);
            });
            }
            
            // Add class for home column
            if (title === 'Home') {
                column.classList.add('home-column');
            }
            
            column.appendChild(itemsList);
            columnsContainer.appendChild(column);
            
            // Update active column
            updateActiveColumn();
            
            // Scroll to show new column
            setTimeout(() => {
                columnsContainer.scrollLeft = columnsContainer.scrollWidth;
            }, 100);
            
            columns.push({ title, items, element: column, hoverImageUrl });
            currentColumnIndex = columns.length - 1;
            console.log('Column added successfully');
        }

        // Update active column
        function updateActiveColumn() {
            const allColumns = document.querySelectorAll('.finder-column');
            allColumns.forEach((col, index) => {
                if (index === currentColumnIndex) {
                    col.classList.add('active');
                } else {
                    col.classList.remove('active');
                }
            });
        }

        // Create item element
        function createItemElement(item) {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'finder-item';
            itemDiv.onclick = () => handleItemClick(item);
            
            // Add hover events for folders with thumbnails
            if (item.type === 'folder' && item.hover_thumbnail_url) {
                itemDiv.onmouseenter = () => showHoverImage(item.hover_thumbnail_url);
                itemDiv.onmouseleave = () => hideHoverImage();
            }
            
            const icon = getIcon(item.type, item);
            
            itemDiv.innerHTML = `
                <div class="item-content">
                    <div class="item-icon">${icon}</div>
                    <div class="item-details">
                        <div class="item-name">${item.name}</div>
                    </div>
                </div>
            `;
            
            return itemDiv;
        }

        // Handle item clicks
        async function handleItemClick(item) {
            console.log('Item clicked:', item);
            if (item.type === 'folder') {
                // Check if this folder is already open in any column
                const existingColumn = columns.find(col => col.title === item.name);
                if (existingColumn) {
                    // Folder is already open, just navigate to it
                    const columnIndex = columns.indexOf(existingColumn);
                    navigateToColumn(columnIndex);
                    return;
                }
                
                // Check if this is a root level folder (from Home column)
                const isFromHomeColumn = currentColumnIndex === 0;
                
                if (isFromHomeColumn) {
                    // Starting a new path - clear all columns except Home
                    clearColumnsExceptHome();
                } else {
                    // Continuing current path - remove all columns after current
                    removeColumnsAfterCurrent();
                }
                
                // Load folder content and add new column
                try {
                    const response = await fetch(`/api/content${item.path}`);
                    const data = await response.json();
                    
                    if (data.status === 'ok') {
                        addColumn(item.name, data.items || [], item.hover_thumbnail_url);
                    } else {
                        addColumn(item.name, [], item.hover_thumbnail_url);
                    }
                } catch (error) {
                    console.error('Failed to load folder:', error);
                    addColumn(item.name, [], item.hover_thumbnail_url);
                }
            } else if (item.type === 'externallink') {
                if (item.url) {
                    window.open(item.url, '_blank');
                }
            } else if (item.type === 'textfile') {
                // Show text content in overlay - GEÄNDERT
                console.log('Text file clicked:', item.name, 'Path:', item.path);
                loadTextFileContent(item.path, item.name);
            } else if (item.type === 'image' && item.url) {
                // Show image in overlay
                console.log('Image clicked:', item.name, item.url);
                showImageOverlay(item.url);
            } else if (item.url) {
                window.open(item.url, '_blank');
            }
        }

        // Clear all columns except Home
        function clearColumnsExceptHome() {
            const columnsContainer = document.getElementById('finderColumns');
            
            // Keep only the first column (Home)
            while (columnsContainer.children.length > 1) {
                columnsContainer.removeChild(columnsContainer.lastChild);
            }
            
            // Reset columns array to only contain Home
            columns = columns.slice(0, 1);
            currentColumnIndex = 0;
        }

        // Remove all columns after current
        function removeColumnsAfterCurrent() {
            const columnsContainer = document.getElementById('finderColumns');
            
            // Remove all columns after current index
            while (columnsContainer.children.length > currentColumnIndex + 1) {
                columnsContainer.removeChild(columnsContainer.lastChild);
            }
            
            // Update columns array
            columns = columns.slice(0, currentColumnIndex + 1);
        }

        // Navigate back to a specific column
        function navigateToColumn(index) {
            if (index >= 0 && index < columns.length) {
                currentColumnIndex = index;
                
                // Update active column
                updateActiveColumn();
                
                // Scroll to show the target column
                const columnsContainer = document.getElementById('finderColumns');
                setTimeout(() => {
                    const targetColumn = columnsContainer.children[index];
                    if (targetColumn) {
                        targetColumn.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }, 100);
            }
        }

        // Get icon for item type
        function getIcon(type, item) {
            if (type === 'image' && item.url) {
                return `<img src="${item.url}" alt="${item.name}" class="image-thumbnail">`;
            }
            
            const icons = {
                folder: '<img src="/assets/icons/Folder.svg" alt="Folder" class="svg-icon">',
                textfile: '<img src="/assets/icons/Textfile.svg" alt="Textfile" class="svg-icon">',
                externallink: '🔗',
                image: '🖼️',
                document: '📑',
                audio: '🎵',
                video: '🎬'
            };
            return icons[type] || '<img src="/assets/icons/Textfile.svg" alt="File" class="svg-icon">';
        }

        // Toggle about page function
function toggleAboutPage() {
    const finderContainer = document.getElementById('finderContainer');
    
    if (finderContainer.classList.contains('slide-down')) {
        // In slide-down state, hide about page
        console.log('Toggle: hiding about page');
        hideAboutPage();
    } else {
        // In normal state, show about page
        console.log('Toggle: showing about page');
        showAboutPage();
    }
    
    // Stop event propagation to prevent double handling
    event.stopPropagation();
}

        // Show about overlay
function showAboutPage() {
    const finderContainer = document.getElementById('finderContainer');
    const aboutOverlay = document.getElementById('aboutOverlay');
    const aboutText = document.getElementById('aboutText');
    
    // Slide finder down with normal animation
    finderContainer.classList.add('slide-down');
    
    // Add bounce effect at the end
    setTimeout(() => {
        finderContainer.classList.add('bounce-end');
    }, 500);
    
    // Show about overlay and fade in text
    setTimeout(() => {
        aboutOverlay.style.display = 'flex';
        setTimeout(() => {
            aboutOverlay.classList.add('active');
            aboutText.classList.add('fade-in');
        }, 10);
    }, 100);
    
    // Load about content
    loadAboutContent();
}
        
        // Hide about overlay
function hideAboutPage() {
    const finderContainer = document.getElementById('finderContainer');
    const aboutOverlay = document.getElementById('aboutOverlay');
    const aboutText = document.getElementById('aboutText');
    
    // Remove any existing bounce classes
    finderContainer.classList.remove('bounce-end', 'bounce-mid');
    
    // Fade out text and start slide back with normal animation
    aboutText.classList.remove('fade-in');
    finderContainer.classList.remove('slide-down');
    
    // Add bounce effect in the middle of return animation
    setTimeout(() => {
        finderContainer.classList.add('bounce-mid');
        // Remove bounce after it completes
        setTimeout(() => {
            finderContainer.classList.remove('bounce-mid');
        }, 200);
    }, 250);
    
    // Hide about overlay after animation
    setTimeout(() => {
        aboutOverlay.classList.remove('active');
        aboutOverlay.style.display = 'none';
    }, 400);
}
        
        // Load about content
        async function loadAboutContent() {
            try {
                const response = await fetch('/api/about');
                const data = await response.json();
                
                if (data.status === 'ok') {
                    const aboutText = document.getElementById('aboutText');
                    aboutText.innerHTML = data.content;
                } else {
                    const aboutText = document.getElementById('aboutText');
                    aboutText.innerHTML = 'About information not available';
                }
            } catch (error) {
                console.error('Failed to load about:', error);
                const aboutText = document.getElementById('aboutText');
                aboutText.innerHTML = 'About information not available';
            }
        }

        // Show hover image
        function showHoverImage(imageUrl) {
            const hoverBg = document.getElementById('finderHoverBg');
            hoverBg.style.backgroundImage = `url('${imageUrl}')`;
            hoverBg.classList.add('active');
        }
        
        // Hide hover image
        function hideHoverImage() {
            const hoverBg = document.getElementById('finderHoverBg');
            hoverBg.classList.remove('active');
        }

        // Show image overlay
        function showImageOverlay(imageUrl) {
            console.log('showImageOverlay called with:', imageUrl);
            const overlay = document.getElementById('imageOverlay');
            const image = document.getElementById('overlayImage');
            
            if (!overlay || !image) {
                console.error('Overlay or image element not found');
                return;
            }
            
            image.src = imageUrl;
            overlay.style.display = 'flex';
            
            // Trigger fade in
            setTimeout(() => {
                overlay.classList.add('active');
            }, 10);
            
            console.log('Image overlay should be visible now');
        }
        
        // Hide image overlay
        function hideImageOverlay() {
            console.log('hideImageOverlay called');
            const overlay = document.getElementById('imageOverlay');
            if (overlay) {
                // Start fade out
                overlay.classList.remove('active');
                
                // Hide after fade completes
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
                
                console.log('Image overlay fading out');
            } else {
                console.error('Image overlay element not found');
            }
        }

        // Text-Overlay Funktionen - NEU HINZUGEFÜGT
        
        // Show text file overlay with better UX
        function showTextOverlay(title, content, path = '') {
            console.log('showTextOverlay called:', title);
            const overlay = document.getElementById('textOverlay');
            const textContent = document.getElementById('textContent');
            
            if (!overlay || !textContent) {
                console.error('Text overlay elements not found');
                return;
            }
            
            // Show loading state
            textContent.innerHTML = `
                <div class="text-loading">
                    <div class="text-spinner"></div>
                    Dokument wird geladen...
                </div>
            `;
            
            // Show overlay
            overlay.style.display = 'flex';
            
            // Trigger fade in
            setTimeout(() => {
                overlay.classList.add('active');
            }, 10);
            
            // Load and display content
            setTimeout(() => {
                displayTextContent(content);
            }, 300);
            
            console.log('Text overlay should be visible now');
        }

        // Display text content with proper formatting
        function displayTextContent(content) {
            const textContent = document.getElementById('textContent');
            
            if (!textContent) {
                console.error('Text content element not found');
                return;
            }
            
            let textContentString = '';
            
            // Handle different content types
            if (typeof content === 'object' && content !== null) {
                // Kirby content object
                if (content.value !== null && content.value !== undefined) {
                    textContentString = content.value;
                } else if (content.content) {
                    textContentString = content.content;
                } else {
                    textContentString = JSON.stringify(content, null, 2);
                }
            } else if (typeof content === 'string') {
                textContentString = content;
            } else {
                textContentString = 'Inhalt konnte nicht geladen werden.';
            }
            
            console.log('Processing text content:', textContentString);
            
                    // Format text content with better paragraph and heading handling
        if (textContentString && textContentString.trim() !== '') {
            // Split into paragraphs and format
            const paragraphs = textContentString
                .replace(/\r\n/g, '\n')
                .replace(/\r/g, '\n')
                .split(/\n\n+/)
                .filter(p => p.trim() !== '');
            
            if (paragraphs.length > 0) {
                const formattedText = paragraphs
                    .map((p, index) => {
                        const trimmedP = p.trim();
                        
                        // First paragraph becomes H1 title
                        if (index === 0) {
                            return `<h1>${trimmedP.replace(/\n/g, '<br>')}</h1>`;
                        }
                        
                        // Check if paragraph starts with # for other headings
                        if (trimmedP.startsWith('# ')) {
                            return `<h1>${trimmedP.substring(2).replace(/\n/g, '<br>')}</h1>`;
                        } else if (trimmedP.startsWith('## ')) {
                            return `<h2>${trimmedP.substring(3).replace(/\n/g, '<br>')}</h2>`;
                        } else if (trimmedP.startsWith('### ')) {
                            return `<h3>${trimmedP.substring(4).replace(/\n/g, '<br>')}</h3>`;
                        } else {
                            return `<p>${p.replace(/\n/g, '<br>')}</p>`;
                        }
                    })
                    .join('');
                textContent.innerHTML = formattedText;
            } else {
                // Single paragraph becomes H1
                const formattedText = textContentString
                    .replace(/\n/g, '<br>');
                textContent.innerHTML = `<h1>${formattedText}</h1>`;
            }
        } else {
                textContent.innerHTML = '<div class="text-error">Kein Inhalt verfügbar oder Inhalt konnte nicht geladen werden.</div>';
            }
        }

        // Load text file content from server
        async function loadTextFileContent(path, title) {
            console.log('Loading text file:', path, title);
            
            try {
                const response = await fetch(`/api/textfile-content${path}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                if (data.status === 'ok' && data.content) {
                    showTextOverlay(title, data.content, path);
                } else {
                    console.error('Failed to load text file content:', data);
                    showTextOverlay(title, 'Fehler beim Laden der Datei.', path);
                }
            } catch (error) {
                console.error('Failed to load text file content:', error);
                showTextOverlay(title, `Fehler beim Laden: ${error.message}`, path);
            }
        }

        // Hide text overlay
        function hideTextOverlay() {
            console.log('hideTextOverlay called');
            const overlay = document.getElementById('textOverlay');
            
            if (overlay) {
                // Start fade out
                overlay.classList.remove('active');
                
                // Hide after fade completes
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
                
                console.log('Text overlay fading out');
            } else {
                console.error('Text overlay element not found');
            }
        }
        
       // Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const aboutOverlay = document.getElementById('aboutOverlay');
        if (aboutOverlay && aboutOverlay.classList.contains('active')) {
            hideAboutPage();
            return;
        }
        
        const imageOverlay = document.getElementById('imageOverlay');
        if (imageOverlay && imageOverlay.classList.contains('active')) {
            hideImageOverlay();
            return;
        }
        
        const textOverlay = document.getElementById('textOverlay');
        if (textOverlay && textOverlay.classList.contains('active')) {
            hideTextOverlay();
            return;
        }
    }
});

// Click handler for finder container when partially visible
document.addEventListener('DOMContentLoaded', function() {
    const finderContainer = document.getElementById('finderContainer');
    const aboutOverlay = document.getElementById('aboutOverlay');
    const siteTitle = document.querySelector('.site-title');
    
    // Hover effect for site title
    if (siteTitle) {
        siteTitle.addEventListener('mouseenter', function() {
            if (!finderContainer.classList.contains('slide-down')) {
                finderContainer.classList.add('hover-effect');
            }
        });
        
        siteTitle.addEventListener('mouseleave', function() {
            finderContainer.classList.remove('hover-effect');
        });
        

    }
    
    // Click handler for finder container when partially visible
    finderContainer.addEventListener('click', function(e) {
        console.log('Finder clicked, slide-down state:', finderContainer.classList.contains('slide-down'));
        console.log('Click target:', e.target);
        console.log('Click currentTarget:', e.currentTarget);
        
        // Only handle clicks if the container is in slide-down state
        if (finderContainer.classList.contains('slide-down')) {
            // Don't trigger if clicking on interactive elements
            if (e.target.closest('.finder-item') || 
                e.target.closest('.finder-columns') ||
                e.target.closest('.site-title')) {
                console.log('Clicked on interactive element, ignoring');
                return;
            }
            
            console.log('Triggering hideAboutPage from finder click');
            // Hide about page and bring finder back
            hideAboutPage();
            e.stopPropagation(); // Prevent event from bubbling to document
            e.preventDefault(); // Prevent any default behavior
        }
    });
    
    // Additional click handler specifically for slide-down state
    finderContainer.addEventListener('mousedown', function(e) {
        if (finderContainer.classList.contains('slide-down')) {
            console.log('Finder mousedown in slide-down state');
            // Don't trigger if clicking on interactive elements (except site-title in slide-down state)
            if (e.target.closest('.finder-item') || 
                e.target.closest('.finder-columns')) {
                console.log('Mousedown on interactive element, ignoring');
                return;
            }
            
            console.log('Triggering hideAboutPage from finder mousedown');
            hideAboutPage();
            e.stopPropagation();
            e.preventDefault();
        }
    });
    
    // Click handler for background image when about is active
    document.addEventListener('click', function(e) {
        const aboutOverlay = document.getElementById('aboutOverlay');
        
        // Only handle clicks if about overlay is active
        if (aboutOverlay && aboutOverlay.classList.contains('active')) {
            // Don't trigger if clicking on the finder
            if (e.target.closest('.finder-container')) {
                return;
            }
            
            // Don't trigger if clicking on the about text content
            if (e.target.closest('.about-text')) {
                return;
            }
            
            console.log('Triggering hideAboutPage from background click');
            // Hide about page and bring finder back
            hideAboutPage();
        }
    });
    
    // Also handle clicks on the background image directly
    const backgroundImage = document.getElementById('backgroundImage');
    if (backgroundImage) {
        backgroundImage.addEventListener('click', function(e) {
            const aboutOverlay = document.getElementById('aboutOverlay');
            const finderContainer = document.getElementById('finderContainer');
            
            // If about overlay is active, hide it
            if (aboutOverlay && aboutOverlay.classList.contains('active')) {
                console.log('Triggering hideAboutPage from background image click');
                hideAboutPage();
            } 
            // If finder is in normal state, show about page
            else if (finderContainer && !finderContainer.classList.contains('slide-down')) {
                console.log('Triggering showAboutPage from background image click');
                showAboutPage();
            }
        });
    }
});
    </script>
</body>
</html>