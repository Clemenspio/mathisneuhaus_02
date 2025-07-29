<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?> - Bild</title>
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
        
        .image-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .image-header {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .image-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .image-icon {
            font-size: 32px;
        }
        
        .image-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .image-content {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .image-display {
            margin-bottom: 30px;
        }
        
        .image-display img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        
        .image-info {
            text-align: left;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .image-tags {
            margin-top: 20px;
        }
        
        .tag {
            display: inline-block;
            background: #007AFF;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        
        .image-description {
            margin-top: 20px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="image-container">
        <div class="image-header">
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
            
            <h1 class="image-title">
                <span class="image-icon">🖼️</span>
                <?= $page->title() ?>
            </h1>
            
            <div class="image-meta">
                <?php if ($page->image_type()->isNotEmpty()): ?>
                    <strong>Typ:</strong> <?= $page->image_type() ?> • 
                <?php endif; ?>
                <strong>Erstellt:</strong> <?= $page->created()->toDate('d.m.Y H:i') ?>
                <?php if ($page->modified()->toDate() !== $page->created()->toDate()): ?>
                    • <strong>Geändert:</strong> <?= $page->modified()->toDate('d.m.Y H:i') ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="image-content">
            <?php if ($page->image()->isNotEmpty()): ?>
                <div class="image-display">
                    <?php $image = $page->image()->toFile(); ?>
                    <img src="<?= $image->url() ?>" 
                         alt="<?= $page->alt_text()->isNotEmpty() ? $page->alt_text() : $page->title() ?>"
                         title="<?= $page->title() ?>">
                </div>
                
                <div class="image-info">
                    <?php if ($page->description()->isNotEmpty()): ?>
                        <div class="image-description">
                            <strong>Beschreibung:</strong><br>
                            <?= $page->description() ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($page->alt_text()->isNotEmpty()): ?>
                        <div class="image-description">
                            <strong>Alt-Text:</strong><br>
                            <?= $page->alt_text() ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="image-description">
                        <strong>Datei:</strong> <?= $image->name() ?><br>
                        <strong>Größe:</strong> <?= $image->niceSize() ?><br>
                        <strong>Dimensionen:</strong> <?= $image->width() ?> × <?= $image->height() ?> px
                    </div>
                    
                    <?php if ($page->tags()->isNotEmpty()): ?>
                        <div class="image-tags">
                            <strong>Tags:</strong><br>
                            <?php foreach ($page->tags()->split(',') as $tag): ?>
                                <span class="tag"><?= trim($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="image-display">
                    <p><em>Kein Bild zugewiesen.</em></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 