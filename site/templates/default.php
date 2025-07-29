<?php
// site/templates/default.php
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mathis Neuhaus - Portfolio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #000;
            color: #1d1d1f;
            overflow: hidden;
            height: 100vh;
        }

        /* Background Image */
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }

        /* Finder Interface */
        .finder-interface {
            position: relative;
            z-index: 10;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .finder-container {
            background: #FFFFFF;
            border: 3px solid #F2F2F2;
            box-shadow: 10px 14px 14px rgba(0, 0, 0, 0.35);
            border-radius: 24px;
            width: 80%;
            height: 80%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Header */
        .finder-header {
            background: #FFFFFF;
            border-bottom: 1px solid #E5E5E5;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .user-icon {
            width: 20px;
            height: 20px;
            color: #333333;
        }

        .site-title {
            font-size: 14px;
            font-weight: 500;
            color: #333333;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .site-title:hover {
            color: #000000;
        }

        /* Main Content */
        .finder-main {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .finder-columns {
            display: flex;
            overflow-x: auto;
            flex: 1;
            scroll-behavior: smooth;
        }

        .finder-column {
            min-width: 280px;
            border-right: 1px solid #E5E5E5;
            background: #FFFFFF;
            overflow-y: auto;
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .finder-column:last-child {
            border-right: none;
        }

        .finder-column.active {
            background: #F8F8F8;
        }

        /* Column headers removed for minimal design */

        .items-list {
            padding: 8px 0;
            position: relative;
            z-index: 5;
        }

        .finder-item {
            padding: 8px 16px;
            cursor: pointer;
            border-bottom: 1px solid #F2F2F7;
            transition: all 0.15s ease;
            position: relative;
            overflow: hidden;
        }

        .finder-item:hover {
            background-color: #F2F2F7;
            transform: translateX(2px);
        }

        .finder-item.selected {
            background-color: #000000;
            color: white;
        }

        .finder-item.selected .item-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }

        .item-content {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .item-icon {
            font-size: 20px;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .finder-item:hover .item-icon {
            transform: scale(1.1);
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
            word-break: break-word;
            line-height: 1.3;
        }

        .item-subtitle {
            font-size: 12px;
            color: #8E8E93;
            line-height: 1.2;
        }

        .folder-arrow {
            font-size: 12px;
            color: #8E8E93;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .finder-item:hover .folder-arrow {
            transform: translateX(2px);
        }

        /* Hover Background within Finder Container */
        .finder-hover-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
            pointer-events: none;
            border-radius: 12px;
        }

        .finder-hover-bg.active {
            opacity: 0.5;
        }

        /* About Overlay */
        .about-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            color: #FFFFFF;
            font-family: 'Univers Selectric One', 'SF Pro', -apple-system, BlinkMacSystemFont, sans-serif;
            transition: opacity 0.3s ease;
        }

        .about-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .about-header .user-icon {
            width: 20px;
            height: 20px;
            color: #FFFFFF;
        }

        .about-header .site-title {
            font-family: 'Univers Selectric One', 'SF Pro', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #FFFFFF;
            margin-right: auto;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .about-header .site-title:hover {
            color: rgba(255, 255, 255, 0.7);
        }

        .back-button {
            background: none;
            border: none;
            font-family: 'SF Pro';
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.2;
            color: #FFFFFF;
            cursor: pointer;
            padding: 0;
            width: auto;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s ease;
        }

        .back-button:hover {
            opacity: 0.7;
        }

        .about-text {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center;
            font-family: 'Univers Selectric One';
            font-style: normal;
            font-weight: 400;
            font-size: 48px;
            line-height: 54px;
            color: #FFFFFF;
        }

        .loading {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #FFFFFF;
            opacity: 0.7;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #FFFFFF;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Image Overlay */
        .image-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .image-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .image-container {
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            background: #FFFFFF;
            border: 3px solid #F2F2F2;
            box-shadow: 0px 0px 34px rgba(0, 0, 0, 0.14);
            border-radius: 2px;
            max-width: 90vw;
            max-height: 90vh;
        }

        .image-container img {
            max-width: calc(90vw - 6px);
            max-height: calc(90vh - 6px);
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }
        .contact-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .contact-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .contact-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            transform: translateY(-20px) scale(0.95);
            transition: all 0.3s ease;
        }

        .contact-overlay.active .contact-content {
            transform: translateY(0) scale(1);
        }

        .contact-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #f2f2f7;
            padding-bottom: 16px;
        }

        .contact-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #1d1d1f;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #8e8e93;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s ease;
        }

        .close-button:hover {
            background-color: #f2f2f7;
        }

        .contact-info {
            space-y: 16px;
        }

        .contact-item {
            margin-bottom: 16px;
        }

        .contact-item strong {
            display: block;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 4px;
        }

        .contact-item a {
            color: #007aff;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .contact-item a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .address {
            white-space: pre-line;
            color: #1d1d1f;
        }

        /* Loading States */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100px;
            color: #8e8e93;
            font-size: 14px;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007aff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #8e8e93;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .finder-container {
                width: 95%;
                height: 90%;
            }
            
            .finder-columns {
                flex-direction: column;
                overflow-x: hidden;
                overflow-y: auto;
            }
            
            .finder-column {
                min-width: 100%;
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                min-height: 200px;
            }
            
            .finder-item {
                padding: 12px 16px;
            }
            
            .item-content {
                gap: 16px;
            }
            
            .item-icon {
                font-size: 24px;
            }
            
            .item-name {
                font-size: 16px;
            }
        }

        /* Focus States */
        .finder-item:focus {
            outline: 2px solid #007aff;
            outline-offset: -2px;
        }

        /* Selection Animation */
        .finder-item.selected {
            animation: selectPulse 0.3s ease;
        }

        @keyframes selectPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
    </style>
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
                        <div class="image-overlay" id="imageOverlay" onclick="hideImageOverlay()">
                            <div class="image-container" onclick="event.stopPropagation()">
                                <img id="overlayImage" src="" alt="">
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
            
            const icon = getIcon(item.type);
            const subtitle = getSubtitle(item);
            
            itemDiv.innerHTML = `
                <div class="item-content">
                    <div class="item-icon">${icon}</div>
                    <div class="item-details">
                        <div class="item-name">${item.name}</div>
                        ${subtitle ? `<div class="item-subtitle">${subtitle}</div>` : ''}
                    </div>
                    ${item.type === 'folder' ? '<div class="folder-arrow">▶</div>' : ''}
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
                            // Show text content in modal (could be implemented)
                            console.log('Text file clicked:', item.name);
                        } else if (item.type === 'image' && item.url) {
                            // Show image in overlay
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
        function getIcon(type) {
            const icons = {
                folder: '📁',
                textfile: '📄',
                externallink: '🔗',
                image: '🖼️',
                document: '📑',
                audio: '🎵',
                video: '🎬'
            };
            return icons[type] || '📄';
        }

        // Get subtitle for item
        function getSubtitle(item) {
            if (item.type === 'folder' && item.item_count !== undefined) {
                return `${item.item_count} items`;
            }
            if (item.size) {
                return item.size;
            }
            if (item.dimensions) {
                return item.dimensions;
            }
            return null;
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
            const overlay = document.getElementById('imageOverlay');
            const image = document.getElementById('overlayImage');
            const container = document.querySelector('.image-container');
            
            image.src = imageUrl;
            
            // Wait for image to load, then adjust container size
            image.onload = function() {
                const imgWidth = image.naturalWidth;
                const imgHeight = image.naturalHeight;
                
                // Calculate max available space
                const maxWidth = window.innerWidth * 0.9 - 6; // 90vw - border only
                const maxHeight = window.innerHeight * 0.9 - 6; // 90vh - border only
                
                // Calculate scale to fit image within bounds
                const scaleX = maxWidth / imgWidth;
                const scaleY = maxHeight / imgHeight;
                const scale = Math.min(scaleX, scaleY, 1); // Don't scale up
                
                // Set container size based on scaled image
                const containerWidth = imgWidth * scale + 6; // image width + border only
                const containerHeight = imgHeight * scale + 6; // image height + border only
                
                container.style.width = containerWidth + 'px';
                container.style.height = containerHeight + 'px';
            };
            
            overlay.classList.add('active');
        }
        
        // Hide image overlay
        function hideImageOverlay() {
            const overlay = document.getElementById('imageOverlay');
            overlay.classList.remove('active');
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
            }
        });
    </script>
</body>
</html> 