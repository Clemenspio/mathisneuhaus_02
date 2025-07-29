<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?> - Finder</title>
    <link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
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