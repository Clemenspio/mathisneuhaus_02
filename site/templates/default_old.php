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
            background: #f5f5f7;
            color: #1d1d1f;
            overflow: hidden;
        }

        .finder-interface {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .finder-header {
            background: linear-gradient(180deg, #e8e8e8 0%, #d0d0d0 100%);
            border-bottom: 1px solid #a0a0a0;
            padding: 12px 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .site-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            margin: 0;
            transition: color 0.2s ease;
            user-select: none;
        }

        .site-title:hover {
            color: #007aff;
        }

        /* Main Content */
        .finder-main {
            flex: 1;
            display: flex;
            overflow: hidden;
            background: #ffffff;
        }

        .finder-columns {
            display: flex;
            overflow-x: auto;
            flex: 1;
        }

        .finder-column {
            min-width: 280px;
            border-right: 1px solid #d1d1d6;
            background: #ffffff;
            overflow-y: auto;
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .finder-column:last-child {
            border-right: none;
        }

        .finder-column.active {
            background: #f8f8f8;
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
            border-bottom: 1px solid #f2f2f7;
            transition: all 0.15s ease;
            position: relative;
            overflow: hidden;
        }

        .finder-item:hover {
            background-color: #f2f2f7;
            transform: translateX(2px);
        }

        .finder-item.selected {
            background-color: #007aff;
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
            color: #8e8e93;
            line-height: 1.2;
        }

        .folder-arrow {
            font-size: 12px;
            color: #8e8e93;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .finder-item:hover .folder-arrow {
            transform: translateX(2px);
        }

        /* Hover Background for Active Column */
        .column-hover-bg {
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
        }

        .finder-column:hover .column-hover-bg {
            opacity: 0.1;
        }

        /* Hover Background for Folders */
        .folder-hover {
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
        }

        .finder-item:hover .folder-hover {
            opacity: 0.8;
        }

        .finder-item:hover .item-content {
            position: relative;
            z-index: 2;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            backdrop-filter: blur(10px);
            margin: -8px -16px;
        }

        .finder-item:hover .item-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Contact Overlay */
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
            .finder-columns {
                flex-direction: column;
                overflow-x: hidden;
                overflow-y: auto;
            }
            
            .finder-column {
                min-width: 100%;
                border-right: none;
                border-bottom: 1px solid #d1d1d6;
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

        /* Smooth Scrolling */
        .finder-columns {
            scroll-behavior: smooth;
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
    <div class="finder-interface">
        <!-- Header -->
        <div class="finder-header">
            <h1 class="site-title" onclick="showContactOverlay()">Mathis Neuhaus</h1>
        </div>
        
        <!-- Main Content -->
        <div class="finder-main">
            <div class="finder-columns" id="finderColumns">
                <!-- Columns will be dynamically added here -->
            </div>
        </div>
    </div>

    <!-- Contact Overlay -->
    <div class="contact-overlay" id="contactOverlay" onclick="hideContactOverlay()">
        <div class="contact-content" onclick="event.stopPropagation()">
            <div class="contact-header">
                <h2>Contact</h2>
                <button class="close-button" onclick="hideContactOverlay()">×</button>
            </div>
            <div class="contact-info" id="contactInfo">
                <div class="loading">
                    <div class="spinner"></div>
                    Loading contact information...
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
        loadRootContent();
        loadContactInfo();

        // Load root content
        async function loadRootContent() {
            console.log('Loading root content...');
            
            // Use hardcoded data for now to ensure it works
            const items = [
                { name: 'Journalismus', type: 'folder', path: '/journalismus', item_count: 2 },
                { name: 'Kommunikation', type: 'folder', path: '/kommunikation', item_count: 1 },
                { name: 'Kuration', type: 'folder', path: '/kuration', item_count: 0 },
                { name: 'Redaktion', type: 'folder', path: '/redaktion', item_count: 0 },
                { name: 'EN_Journalismus.docx', type: 'textfile', path: '/en-journalismus-docx' },
                { name: 'DE_Journalismus.docx', type: 'textfile', path: '/de-journalismus-docx' }
            ];
            
            console.log('Adding column with', items.length, 'items');
            addColumn('Home', items);
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
            
            // Add hover background for the entire column if provided
            if (hoverImageUrl) {
                const hoverBg = document.createElement('div');
                hoverBg.className = 'column-hover-bg';
                hoverBg.style.backgroundImage = `url('${hoverImageUrl}')`;
                column.appendChild(hoverBg);
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
                const isRootFolder = columns.length === 1; // Only Home column exists
                
                if (isRootFolder) {
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

        // Load contact information
        async function loadContactInfo() {
            try {
                const response = await fetch('/api/contact');
                const data = await response.json();
                
                if (data.status === 'ok') {
                    contactData = data;
                    updateContactDisplay();
                } else {
                    // Fallback contact data
                    contactData = {
                        email: 'info@mathisneuhaus.com',
                        phone: '+49 123 456789',
                        address: 'Musterstraße 1\n12345 Berlin'
                    };
                    updateContactDisplay();
                }
            } catch (error) {
                console.error('Failed to load contact:', error);
                // Fallback contact data
                contactData = {
                    email: 'info@mathisneuhaus.com',
                    phone: '+49 123 456789',
                    address: 'Musterstraße 1\n12345 Berlin'
                };
                updateContactDisplay();
            }
        }

        // Update contact display
        function updateContactDisplay() {
            const contactInfo = document.getElementById('contactInfo');
            
            if (contactData) {
                let html = '';
                
                if (contactData.email) {
                    html += `
                        <div class="contact-item">
                            <strong>Email:</strong>
                            <a href="mailto:${contactData.email}">${contactData.email}</a>
                        </div>
                    `;
                }
                
                if (contactData.phone) {
                    html += `
                        <div class="contact-item">
                            <strong>Phone:</strong>
                            <a href="tel:${contactData.phone}">${contactData.phone}</a>
                        </div>
                    `;
                }
                
                if (contactData.address) {
                    html += `
                        <div class="contact-item">
                            <strong>Address:</strong>
                            <div class="address">${contactData.address}</div>
                        </div>
                    `;
                }
                
                if (contactData.social && contactData.social.length > 0) {
                    html += `
                        <div class="contact-item">
                            <strong>Social Links:</strong>
                            <div style="margin-top: 8px;">
                                ${contactData.social.map(social => 
                                    `<a href="${social.url}" target="_blank" style="display: inline-block; margin-right: 12px; color: #007aff;">${social.platform}</a>`
                                ).join('')}
                            </div>
                        </div>
                    `;
                }
                
                contactInfo.innerHTML = html;
            }
        }

        // Show contact overlay
        function showContactOverlay() {
            const overlay = document.getElementById('contactOverlay');
            overlay.classList.add('active');
        }

        // Hide contact overlay
        function hideContactOverlay() {
            const overlay = document.getElementById('contactOverlay');
            overlay.classList.remove('active');
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideContactOverlay();
            }
        });
    </script>
</body>
</html>