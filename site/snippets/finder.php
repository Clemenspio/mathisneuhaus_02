<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?> - Finder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        
        .finder-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .finder-header {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .finder-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .finder-path {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .finder-content {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .finder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .finder-item {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        
        .finder-item:hover {
            border-color: #007AFF;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .finder-item-icon {
            font-size: 48px;
            margin-bottom: 10px;
            display: block;
        }
        
        .finder-item-title {
            font-weight: 500;
            margin-bottom: 5px;
            word-break: break-word;
        }
        
        .finder-item-type {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        
        .finder-item-description {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            line-height: 1.3;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .breadcrumb-item {
            color: #007AFF;
            text-decoration: none;
        }
        
        .breadcrumb-separator {
            color: #999;
        }
        
        .breadcrumb-current {
            color: #333;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="finder-container">
        <div class="finder-header">
            <h1 class="finder-title"><?= $page->title() ?></h1>
            <?php if ($page->description()->isNotEmpty()): ?>
                <p><?= $page->description() ?></p>
            <?php endif; ?>
            
            <!-- Breadcrumb Navigation -->
            <div class="breadcrumb">
                <a href="<?= $site->url() ?>" class="breadcrumb-item">🏠 Start</a>
                <?php 
                $parents = $page->parents();
                foreach ($parents as $parent): ?>
                    <span class="breadcrumb-separator">/</span>
                    <a href="<?= $parent->url() ?>" class="breadcrumb-item"><?= $parent->title() ?></a>
                <?php endforeach; ?>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current"><?= $page->title() ?></span>
            </div>
        </div>
        
        <div class="finder-content">
            <?php 
            $children = $page->children();
            $files = $page->files();
            
            if ($children->count() > 0 || $files->count() > 0): ?>
                <div class="finder-grid">
                    <!-- Ordner anzeigen -->
                    <?php foreach ($children as $child): ?>
                        <a href="<?= $child->url() ?>" class="finder-item">
                            <?php 
                            $icon = '📁';
                            if ($child->intendedTemplate() === 'folder' && $child->icon()->isNotEmpty()) {
                                $iconMap = [
                                    'folder' => '📁',
                                    'documents' => '📄',
                                    'images' => '🖼️',
                                    'music' => '🎵',
                                    'videos' => '🎬',
                                    'downloads' => '⬇️',
                                    'applications' => '🖥️'
                                ];
                                $icon = $iconMap[$child->icon()->value()] ?? '📁';
                            } elseif ($child->intendedTemplate() === 'document') {
                                $icon = '📄';
                            } elseif ($child->intendedTemplate() === 'image') {
                                $icon = '🖼️';
                            } elseif ($child->intendedTemplate() === 'link') {
                                $icon = '🔗';
                            }
                            ?>
                            <span class="finder-item-icon"><?= $icon ?></span>
                            <div class="finder-item-title"><?= $child->title() ?></div>
                            <div class="finder-item-type">
                                <?php 
                                $typeMap = [
                                    'folder' => 'Ordner',
                                    'document' => 'Dokument',
                                    'image' => 'Bild',
                                    'link' => 'Link'
                                ];
                                echo $typeMap[$child->intendedTemplate()] ?? 'Seite';
                                ?>
                            </div>
                            <?php if ($child->description()->isNotEmpty()): ?>
                                <div class="finder-item-description"><?= $child->description() ?></div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                    
                    <!-- Dateien anzeigen -->
                    <?php foreach ($files as $file): ?>
                        <a href="<?= $file->url() ?>" class="finder-item" target="_blank">
                            <?php 
                            $icon = '📎';
                            $extension = $file->extension();
                            $iconMap = [
                                'pdf' => '📋',
                                'doc' => '📝',
                                'docx' => '📝',
                                'xls' => '📊',
                                'xlsx' => '📊',
                                'ppt' => '📽️',
                                'pptx' => '📽️',
                                'jpg' => '🖼️',
                                'jpeg' => '🖼️',
                                'png' => '🖼️',
                                'gif' => '🖼️',
                                'mp4' => '🎬',
                                'mov' => '🎬',
                                'mp3' => '🎵',
                                'wav' => '🎵',
                                'zip' => '📦',
                                'txt' => '📄'
                            ];
                            $icon = $iconMap[$extension] ?? '📎';
                            ?>
                            <span class="finder-item-icon"><?= $icon ?></span>
                            <div class="finder-item-title"><?= $file->name() ?></div>
                            <div class="finder-item-type"><?= strtoupper($extension) ?> Datei</div>
                            <div class="finder-item-description"><?= $file->niceSize() ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📁</div>
                    <h3>Dieser Ordner ist leer</h3>
                    <p>Fügen Sie Ordner oder Dateien hinzu, um zu beginnen.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 