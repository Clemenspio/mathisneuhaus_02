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
            
                <!-- Finder Interface -->
                <div class="finder-interface">
                    <div class="finder-container">
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
                                <div class="text-content" id="textContent">
                                    <!-- Text content will be loaded here -->
                                </div>
                            </div>
                        </div>
            <!-- Header -->
                                    <div class="finder-header">
                            <svg class="user-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="site-title" onclick="showAboutPage()">Mathis Neuhaus</span>
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
        let lastClickedItem = null;

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
            
            // Update selected items in parent columns
            updateSelectedItemsInParentColumns();
        }

        // Mark the clicked item as selected immediately
        function markClickedItemAsSelected(item) {
            // Update the selected items to include the clicked item
            updateSelectedItemsInParentColumns();
        }

        // Update selected items in parent columns to show current path
        function updateSelectedItemsInParentColumns() {
            const allColumns = document.querySelectorAll('.finder-column');
            
            // Clear all selected states first
            document.querySelectorAll('.finder-item.selected').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Mark items as selected based on current path
            for (let i = 0; i <= currentColumnIndex; i++) {
                const column = allColumns[i];
                if (column && i < columns.length) {
                    const columnData = columns[i];
                    const items = column.querySelectorAll('.finder-item');
                    
                    // Find the item that corresponds to the next column in the path
                    if (i < currentColumnIndex && i + 1 < columns.length) {
                        const nextColumnTitle = columns[i + 1].title;
                        items.forEach(item => {
                            const itemName = item.querySelector('.item-name').textContent;
                            if (itemName === nextColumnTitle) {
                                item.classList.add('selected');
                            }
                        });
                    }
                    
                    // Also mark the current column's title in this column if it exists
                    if (i < currentColumnIndex) {
                        const currentColumnTitle = columns[currentColumnIndex].title;
                        items.forEach(item => {
                            const itemName = item.querySelector('.item-name').textContent;
                            if (itemName === currentColumnTitle) {
                                item.classList.add('selected');
                            }
                        });
                    }
                    
                    // Mark the current column's selected item
                    if (i === currentColumnIndex) {
                        // Mark the item that corresponds to the current column title
                        const currentColumnTitle = columns[i].title;
                        items.forEach(item => {
                            const itemName = item.querySelector('.item-name').textContent;
                            if (itemName === currentColumnTitle) {
                                item.classList.add('selected');
                            }
                        });
                    }
                }
            }
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
                    // Set lastClickedItem first
                    lastClickedItem = item;
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
                
                // Set lastClickedItem for new folders
                lastClickedItem = item;
                                    } else if (item.type === 'externallink') {
                            if (item.url) {
                                window.open(item.url, '_blank');
                            }
                        } else if (item.type === 'textfile') {
                            // Show text content in overlay
                            console.log('Text file clicked:', item.name);
                            // Always try to load content from the textfile.txt
                            loadTextFileContent(item.path);
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
            
            // Update active column and selected items
            updateActiveColumn();
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
            
            // Update active column and selected items
            updateActiveColumn();
        }

        // Navigate back to a specific column
        function navigateToColumn(index) {
            if (index >= 0 && index < columns.length) {
                currentColumnIndex = index;
                
                // Update active column and selected items
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





        // Show about overlay
        function showAboutPage() {
            const finderContainer = document.querySelector('.finder-container');
            const finderColumns = document.getElementById('finderColumns');
            const finderHeader = document.querySelector('.finder-header');
            
            // Create about content
            const aboutContent = document.createElement('div');
            aboutContent.className = 'about-overlay';
            aboutContent.style.opacity = '0';
            aboutContent.innerHTML = `
                <div class="about-header">
                    <button class="back-button" onclick="hideAboutPage()">←</button>
                    <span class="site-title" onclick="hideAboutPage()">FILES</span>
                </div>
                <div class="about-text" id="aboutText">
                    <div class="loading">
                        <div class="spinner"></div>
                        Loading about information...
                    </div>
                </div>
            `;
            
            finderContainer.appendChild(aboutContent);
            
            // Fade out finder content
            finderColumns.style.transition = 'opacity 0.3s ease';
            finderHeader.style.transition = 'opacity 0.3s ease';
            finderColumns.style.opacity = '0';
            finderHeader.style.opacity = '0';
            
            // Make container black with transition
            finderContainer.style.transition = 'background-color 0.3s ease, border 0.3s ease, box-shadow 0.3s ease';
            finderContainer.style.backgroundColor = '#000000';
            finderContainer.style.border = '3px solid #000000';
            finderContainer.style.boxShadow = '10px 14px 14px rgba(0, 0, 0, 0.35)';
            
            // Fade in about content
            setTimeout(() => {
                finderColumns.style.display = 'none';
                finderHeader.style.display = 'none';
                aboutContent.style.opacity = '1';
            }, 300);
            
            // Load about content
            loadAboutContent();
        }
        
        // Hide about overlay
        function hideAboutPage() {
            const finderContainer = document.querySelector('.finder-container');
            const finderColumns = document.getElementById('finderColumns');
            const finderHeader = document.querySelector('.finder-header');
            const aboutOverlay = document.querySelector('.about-overlay');
            
            if (aboutOverlay) {
                // Fade out about content
                aboutOverlay.style.transition = 'opacity 0.3s ease';
                aboutOverlay.style.opacity = '0';
                
                // Restore container styling with transition
                finderContainer.style.transition = 'background-color 0.3s ease, border 0.3s ease, box-shadow 0.3s ease';
                finderContainer.style.backgroundColor = '';
                finderContainer.style.border = '';
                finderContainer.style.boxShadow = '';
                
                // Show finder content with fade in
                setTimeout(() => {
                    finderColumns.style.display = 'flex';
                    finderHeader.style.display = 'flex';
                    finderColumns.style.transition = 'opacity 0.3s ease';
                    finderHeader.style.transition = 'opacity 0.3s ease';
                    finderColumns.style.opacity = '1';
                    finderHeader.style.opacity = '1';
                    
                    // Remove about overlay after fade
                    setTimeout(() => {
                        if (aboutOverlay) {
                            aboutOverlay.remove();
                        }
                    }, 300);
                }, 300);
            }
        }
        
        // Load about content
        async function loadAboutContent() {
            try {
                const response = await fetch('/api/about');
                const data = await response.json();
                
                if (data.status === 'ok') {
                    const aboutText = document.getElementById('aboutText');
                    aboutText.innerHTML = data.content.replace(/\n/g, '<br>');
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
        
        // Show text file overlay
        function showTextOverlay(content) {
            console.log('showTextOverlay called with content');
            const overlay = document.getElementById('textOverlay');
            const textContent = document.getElementById('textContent');
            
            if (!overlay || !textContent) {
                console.error('Text overlay or content element not found');
                return;
            }
            
            // Show loading state
            textContent.innerHTML = '<div class="loading"><div class="spinner"></div>Loading text content...</div>';
            overlay.style.display = 'flex';
            
            // Trigger fade in
            setTimeout(() => {
                overlay.classList.add('active');
            }, 10);
            
            // Load text content directly
            loadTextContent(content);
            
            console.log('Text overlay should be visible now');
        }
        
        // Load text file content from server
        async function loadTextFileContent(path) {
            try {
                const response = await fetch(`/api/textfile-content${path}`);
                const data = await response.json();
                
                if (data.status === 'ok' && data.content) {
                    showTextOverlay(data.content);
                } else {
                    console.error('Failed to load text file content');
                }
            } catch (error) {
                console.error('Failed to load text file content:', error);
            }
        }

        // Load text content
        function loadTextContent(content) {
            console.log('loadTextContent called with:', content);
            const textContent = document.getElementById('textContent');
            if (textContent && content) {
                // Check if content is an object and extract the text
                let textContentString = '';
                if (typeof content === 'object') {
                    // If it's an object, try to get the content field
                    console.log('Content object keys:', Object.keys(content));
                    console.log('Content object:', content);
                    
                    // Try to get the content from the Kirby content object
                    if (content.value !== null && content.value !== undefined) {
                        textContentString = content.value;
                    } else if (content.content) {
                        textContentString = content.content;
                    } else {
                        textContentString = JSON.stringify(content);
                    }
                } else {
                    textContentString = content;
                }
                
                console.log('Text content string:', textContentString);
                
                // Format the content directly
                const formattedText = textContentString
                    .replace(/\n\n/g, '</p><p>')
                    .replace(/\n/g, '<br>');
                
                if (formattedText && formattedText !== '[object Object]') {
                    textContent.innerHTML = `<p>${formattedText}</p>`;
                } else {
                    textContent.innerHTML = '<div class="error">Content format not supported</div>';
                }
            } else {
                textContent.innerHTML = '<div class="error">No content available</div>';
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
                const aboutOverlay = document.querySelector('.about-overlay');
                if (aboutOverlay) {
                    hideAboutPage();
                }
                
                const imageOverlay = document.getElementById('imageOverlay');
                if (imageOverlay && imageOverlay.classList.contains('active')) {
                    hideImageOverlay();
                }
                
                const textOverlay = document.getElementById('textOverlay');
                if (textOverlay && textOverlay.classList.contains('active')) {
                    hideTextOverlay();
                }
            }
        });
    </script>
</body>
</html> 