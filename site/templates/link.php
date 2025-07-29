<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?> - Link</title>
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
        
        .link-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .link-header {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .link-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .link-icon {
            font-size: 32px;
        }
        
        .link-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .link-content {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .link-display {
            margin-bottom: 30px;
        }
        
        .link-button {
            display: inline-block;
            background: #007AFF;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .link-button:hover {
            background: #0056CC;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .link-url {
            margin-top: 15px;
            color: #666;
            font-size: 14px;
            word-break: break-all;
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
        
        .link-info {
            text-align: left;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .link-tags {
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
        
        .link-description {
            margin-top: 20px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="link-container">
        <div class="link-header">
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
            
            <h1 class="link-title">
                <span class="link-icon">🔗</span>
                <?= $page->title() ?>
            </h1>
            
            <div class="link-meta">
                <?php if ($page->link_type()->isNotEmpty()): ?>
                    <strong>Typ:</strong> <?= $page->link_type() ?> • 
                <?php endif; ?>
                <strong>Erstellt:</strong> <?= $page->created()->toDate('d.m.Y H:i') ?>
                <?php if ($page->modified()->toDate() !== $page->created()->toDate()): ?>
                    • <strong>Geändert:</strong> <?= $page->modified()->toDate('d.m.Y H:i') ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="link-content">
            <?php if ($page->url()->isNotEmpty()): ?>
                <div class="link-display">
                    <a href="<?= $page->url() ?>" 
                       class="link-button"
                       <?= $page->open_in_new_tab()->isTrue() ? 'target="_blank"' : '' ?>>
                        🔗 Link öffnen
                    </a>
                    <div class="link-url"><?= $page->url() ?></div>
                </div>
                
                <div class="link-info">
                    <?php if ($page->description()->isNotEmpty()): ?>
                        <div class="link-description">
                            <strong>Beschreibung:</strong><br>
                            <?= $page->description() ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="link-description">
                        <strong>Einstellungen:</strong><br>
                        <?= $page->open_in_new_tab()->isTrue() ? 'Öffnet in neuem Tab' : 'Öffnet im gleichen Tab' ?>
                    </div>
                    
                    <?php if ($page->tags()->isNotEmpty()): ?>
                        <div class="link-tags">
                            <strong>Tags:</strong><br>
                            <?php foreach ($page->tags()->split(',') as $tag): ?>
                                <span class="tag"><?= trim($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="link-display">
                    <p><em>Keine URL angegeben.</em></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 