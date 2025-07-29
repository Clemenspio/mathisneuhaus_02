<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?> - Dokument</title>
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
        
        .document-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .document-header {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .document-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .document-icon {
            font-size: 32px;
        }
        
        .document-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .document-content {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            line-height: 1.6;
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
        
        .document-tags {
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
    </style>
</head>
<body>
    <div class="document-container">
        <div class="document-header">
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
            
            <h1 class="document-title">
                <span class="document-icon">📄</span>
                <?= $page->title() ?>
            </h1>
            
            <div class="document-meta">
                <?php if ($page->file_type()->isNotEmpty()): ?>
                    <strong>Typ:</strong> <?= $page->file_type() ?> • 
                <?php endif; ?>
                <strong>Erstellt:</strong> <?= $page->created()->toDate('d.m.Y H:i') ?>
                <?php if ($page->modified()->toDate() !== $page->created()->toDate()): ?>
                    • <strong>Geändert:</strong> <?= $page->modified()->toDate('d.m.Y H:i') ?>
                <?php endif; ?>
            </div>
            
            <?php if ($page->description()->isNotEmpty()): ?>
                <p><?= $page->description() ?></p>
            <?php endif; ?>
        </div>
        
        <div class="document-content">
            <?php if ($page->content()->isNotEmpty()): ?>
                <?= $page->content()->kt() ?>
            <?php else: ?>
                <p><em>Dieses Dokument hat noch keinen Inhalt.</em></p>
            <?php endif; ?>
            
            <?php if ($page->tags()->isNotEmpty()): ?>
                <div class="document-tags">
                    <strong>Tags:</strong><br>
                    <?php foreach ($page->tags()->split(',') as $tag): ?>
                        <span class="tag"><?= trim($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 