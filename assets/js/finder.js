/**
 * Finder JavaScript
 * Saubere Trennung der JavaScript-Logik
 */

// Global state
let currentPath = '/';
let columns = [];
let contactData = null;
let currentColumnIndex = 0;
let clickedPath = []; // Array to track clicked folders in path

// Initialize the finder
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing finder...');
    loadBackgroundImage();
    loadRootContent();
    
    // Add click event for background image to toggle about page
    const backgroundImage = document.getElementById('backgroundImage');
    if (backgroundImage) {
        backgroundImage.addEventListener('click', function(e) {
            // Only trigger if clicking directly on the background, not on overlays
            if (e.target === backgroundImage) {
                toggleAboutPage();
            }
        });
    }
    
    // Add hover effects only for the finder header (top bar)
    const finderHeader = document.querySelector('.finder-header');
    if (finderHeader) {
        finderHeader.addEventListener('mouseenter', function() {
            const finderContainer = document.getElementById('finderContainer');
            if (finderContainer && !finderContainer.classList.contains('slide-down')) {
                finderContainer.classList.add('hover-effect');
            }
        });
        
        finderHeader.addEventListener('mouseleave', function() {
            const finderContainer = document.getElementById('finderContainer');
            if (finderContainer) {
                finderContainer.classList.remove('hover-effect');
            }
        });
    }
    
    // Add click event for about overlay to close when clicking outside about text
    const aboutOverlay = document.getElementById('aboutOverlay');
    if (aboutOverlay) {
        aboutOverlay.addEventListener('click', function(e) {
            console.log('About overlay clicked:', e.target);
            
            // Close about page when clicking anywhere except the about text itself
            const aboutText = document.getElementById('aboutText');
            
            console.log('Clicked element:', e.target);
            console.log('About text element:', aboutText);
            console.log('Is click inside about text:', aboutText && aboutText.contains(e.target));
            
            if (!aboutText || !aboutText.contains(e.target)) {
                console.log('Closing about page');
                hideAboutPage();
            } else {
                console.log('Click was inside about text, not closing');
            }
        });
    }
});

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
    console.log('Current columns before adding:', columns.length);
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
    
    columns.push({ title, items, element: column, hoverImageUrl });
    currentColumnIndex = columns.length - 1;
    
    // Update active column
    updateActiveColumn();
    
    // Scroll to show new column
    setTimeout(() => {
        columnsContainer.scrollLeft = columnsContainer.scrollWidth;
    }, 100);
    
    console.log('Column added successfully, currentColumnIndex:', currentColumnIndex);
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
    
    // Check if this item is in the clicked path
    if (item.type === 'folder' && clickedPath.includes(item.path)) {
        itemDiv.classList.add('active-path');
    }
    
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
    console.log('Current clickedPath before:', clickedPath);
    
    // Always hide hover image when clicking
    hideHoverImage();
    
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
        console.log('isFromHomeColumn:', isFromHomeColumn, 'currentColumnIndex:', currentColumnIndex);
        
        // Check if this item is already in the current path
        const currentPathIndex = clickedPath.indexOf(item.path);
        console.log('currentPathIndex:', currentPathIndex, 'clickedPath:', clickedPath);
        
        if (isFromHomeColumn) {
            // Starting a new path from Home
            console.log('Starting new path from Home');
            clearColumnsExceptHome();
            clickedPath = [item.path];
        } else if (currentPathIndex !== -1) {
            // Item is already in path - navigate to it
            console.log('Navigating to existing item in path');
            removeColumnsAfterCurrent();
            clickedPath = clickedPath.slice(0, currentPathIndex + 1);
        } else {
            // Check if this is a direct subfolder of the current path
            const currentPath = clickedPath[clickedPath.length - 1];
            const currentPathParts = currentPath.split('/').filter(part => part);
            const itemPathParts = item.path.split('/').filter(part => part);
            
            // A direct subfolder should have exactly one more path segment AND be from the same parent
            const isDirectSubfolder = itemPathParts.length === currentPathParts.length + 1 && 
                                   item.path.startsWith(currentPath + '/');
            
            // Check if this is a sibling folder (same level as current)
            const currentParentPath = clickedPath.length > 0 ? clickedPath[clickedPath.length - 1].split('/').slice(0, -1).join('/') : '';
            const itemParentPath = item.path.split('/').slice(0, -1).join('/');
            const isSibling = currentParentPath && itemParentPath === currentParentPath;
            
            // Check if this is from a different root hierarchy
            const currentRoot = clickedPath.length > 0 ? clickedPath[0].split('/')[1] : '';
            const itemRoot = item.path.split('/')[1];
            const isDifferentRoot = currentRoot && itemRoot && currentRoot !== itemRoot;
            
            console.log('isDirectSubfolder:', isDirectSubfolder, 'isSibling:', isSibling, 'isDifferentRoot:', isDifferentRoot);
            console.log('currentPathParts:', currentPathParts, 'itemPathParts:', itemPathParts);
            console.log('currentRoot:', currentRoot, 'itemRoot:', itemRoot);
            
            if (isDifferentRoot) {
                // This is from a different root hierarchy - clear everything and start fresh
                console.log('Different root hierarchy - clearing everything and starting fresh');
                clearColumnsExceptHome();
                clickedPath = [item.path];
            } else if (isDirectSubfolder) {
                // This is a direct subfolder - continue the path
                console.log('Adding direct subfolder to current path');
                clickedPath.push(item.path);
            } else if (isSibling) {
                // This is a sibling folder - replace current with this one
                console.log('Replacing current folder with sibling');
                removeColumnsAfterCurrent();
                clickedPath[clickedPath.length - 1] = item.path;
                console.log('After sibling replacement - clickedPath:', clickedPath);
            } else {
                // This is from a different hierarchy - clear everything and start fresh
                console.log('Different hierarchy - clearing everything and starting fresh');
                clearColumnsExceptHome();
                clickedPath = [item.path];
            }
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
        
        // Update all columns to reflect new path
        console.log('Final clickedPath after navigation:', clickedPath);
        setTimeout(() => {
            updateAllColumnsForPath();
        }, 100);
    } else if (item.type === 'externallink') {
        if (item.url) {
            window.open(item.url, '_blank');
        }
    } else if (item.type === 'textfile') {
        // Show text content in overlay
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
    
    console.log('Before clearing - columns.length:', columns.length, 'container children:', columnsContainer.children.length);
    
    // Hide hover image when clearing
    hideHoverImage();
    
    // Keep only the first column (Home)
    while (columnsContainer.children.length > 1) {
        columnsContainer.removeChild(columnsContainer.lastChild);
    }
    
    // Reset columns array to only contain Home
    columns = columns.slice(0, 1);
    currentColumnIndex = 0;
    
    // Reset clicked path
    clickedPath = [];
    
    console.log('After clearing - columns.length:', columns.length, 'container children:', columnsContainer.children.length);
    console.log('Cleared all columns except Home, new clickedPath:', clickedPath);
}

// Remove all columns after current
function removeColumnsAfterCurrent() {
    const columnsContainer = document.getElementById('finderColumns');
    
    console.log('Before removing - columns.length:', columns.length, 'currentColumnIndex:', currentColumnIndex, 'container children:', columnsContainer.children.length);
    
    // Hide hover image when removing columns
    hideHoverImage();
    
    // Remove all columns after current index
    while (columnsContainer.children.length > currentColumnIndex + 1) {
        columnsContainer.removeChild(columnsContainer.lastChild);
    }
    
    // Update columns array
    columns = columns.slice(0, currentColumnIndex + 1);
    
    console.log('After removing - columns.length:', columns.length, 'container children:', columnsContainer.children.length);
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

// Function to update all columns to reflect current path
function updateAllColumnsForPath() {
    console.log('Updating all columns for path:', clickedPath);
    columns.forEach(column => {
        const itemsList = column.element.querySelector('.items-list');
        if (itemsList) {
            // Clear existing items
            itemsList.innerHTML = '';
            
            // Recreate items with updated path highlighting
            column.items.forEach(item => {
                const itemElement = createItemElement(item);
                itemsList.appendChild(itemElement);
            });
        }
    });
}

// Hover image functions
function showHoverImage(imageUrl) {
    const hoverBg = document.getElementById('finderHoverBg');
    hoverBg.style.backgroundImage = `url('${imageUrl}')`;
    hoverBg.classList.add('active');
}

function hideHoverImage() {
    const hoverBg = document.getElementById('finderHoverBg');
    hoverBg.classList.remove('active');
}

// Image overlay functions
function showImageOverlay(imageUrl) {
    console.log('showImageOverlay called with:', imageUrl);
    const overlay = document.getElementById('imageOverlay');
    const image = document.getElementById('overlayImage');
    
    if (overlay && image) {
        image.src = imageUrl;
        overlay.style.display = 'flex';
        
        setTimeout(() => {
            overlay.classList.add('active');
        }, 10);
    }
}

function hideImageOverlay() {
    const overlay = document.getElementById('imageOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

// Text overlay functions
function showTextOverlay(title, content, path) {
    console.log('showTextOverlay called with:', title, path);
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
        showTextOverlay(title, 'Fehler beim Laden der Datei.', path);
    }
}

function hideTextOverlay() {
    const overlay = document.getElementById('textOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

// About page functions
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
    if (event) {
        event.stopPropagation();
    }
}

function showAboutPage() {
    const finderContainer = document.getElementById('finderContainer');
    const aboutOverlay = document.getElementById('aboutOverlay');
    const aboutText = document.getElementById('aboutText');
    
    // Prevent body scrolling when about page is open
    document.body.style.overflow = 'hidden';
    
    // Slide finder down with normal animation
    finderContainer.classList.add('slide-down');
    
    // Show about overlay
    aboutOverlay.style.display = 'flex';
    
    // Load about content
    loadAboutContent();
    
    // Fade in about text
    setTimeout(() => {
        aboutOverlay.classList.add('active');
        aboutText.classList.add('fade-in');
    }, 300);
}

function hideAboutPage() {
    const finderContainer = document.getElementById('finderContainer');
    const aboutOverlay = document.getElementById('aboutOverlay');
    const aboutText = document.getElementById('aboutText');
    
    // Re-enable body scrolling
    document.body.style.overflow = '';
    
    // Fade out about text
    aboutText.classList.remove('fade-in');
    aboutOverlay.classList.remove('active');
    
    // Slide finder back up
    finderContainer.classList.remove('slide-down');
    
    // Hide about overlay after animation
    setTimeout(() => {
        aboutOverlay.style.display = 'none';
    }, 300);
}

async function loadAboutContent() {
    const aboutText = document.getElementById('aboutText');
    
    try {
        const response = await fetch('/api/about');
        const data = await response.json();
        
        if (data.status === 'ok') {
            // Process raw content from Kirby
            let content = data.content;
            
            console.log('Original content:', content);
            
            // If content is an object with value property, extract it
            if (typeof content === 'object' && content.value) {
                content = content.value;
            }
            
            // Convert Kirby email syntax to HTML links
            content = content.replace(/\(email:\s*([^\s]+)\s+text:\s*([^)]+)\)/gi, '<a href="mailto:$1" style="color: #FFFFFF; text-decoration: underline;">$2</a>');
            
            // Convert Kirby link syntax to HTML links
            content = content.replace(/\(link:\s*([^\s]+)\s+text:\s*([^)]+)\)/gi, '<a href="$1" style="color: #FFFFFF; text-decoration: underline;" target="_blank">$2</a>');
            
            // Convert double line breaks to paragraph breaks
            content = content.replace(/\n\n/g, '</p><p>');
            
            // Convert single line breaks to <br>
            content = content.replace(/\n/g, '<br>');
            
            // Wrap in paragraph tags
            content = '<p>' + content + '</p>';
            
            console.log('Final content:', content);
            
            aboutText.innerHTML = content;
        } else {
            aboutText.innerHTML = 'About information not available';
        }
    } catch (error) {
        console.error('Failed to load about:', error);
        aboutText.innerHTML = 'About information not available';
    }
}

// Add click handler for about overlay to close on click (except links)
document.addEventListener('DOMContentLoaded', function() {
    const aboutOverlay = document.getElementById('aboutOverlay');
    
    if (aboutOverlay) {
        aboutOverlay.addEventListener('click', function(e) {
            // Only handle clicks if about overlay is active
            if (aboutOverlay.classList.contains('active')) {
                // Don't trigger if clicking on links
                if (e.target.tagName === 'A' || e.target.closest('a')) {
                    console.log('Clicked on link, not closing about page');
                    return;
                }
                
                console.log('Clicked on about overlay, closing about page');
                hideAboutPage();
            }
        });
    }
}); 