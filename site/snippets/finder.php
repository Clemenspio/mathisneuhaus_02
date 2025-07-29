<?php
/**
 * Finder Snippet
 * 
 * Dieses Snippet generiert die Finder-Ansicht für Projekte und Dateien.
 * Hover Background Images werden automatisch aus der Anzeige gefiltert.
 * 
 * Verwendung: <?php snippet('finder', ['pages' => $pages]) ?>
 */

// Default pages falls keine übergeben wurden
$pages = $pages ?? site()->children()->visible();
?>

<div class="finder-container">
    <?php foreach($pages as $item): ?>
        <div class="finder-item" 
             data-hover-bg="<?= $item->hover_background_images()->toFile() ? $item->hover_background_images()->toFile()->url() : '' ?>"
             data-url="<?= $item->url() ?>">
            
            <!-- Folder/Page Icon und Name -->
            <div class="finder-item-header">
                <span class="finder-icon">📁</span>
                <span class="finder-name"><?= $item->title() ?></span>
            </div>

            <!-- Zeige Dateien innerhalb des Folders, aber NICHT die Hover Background Images -->
            <?php if($item->hasFiles()): ?>
                <?php 
                // WICHTIG: Hier filtern wir die Hover Background Images raus
                $visibleFiles = $item->files()->filter(function($file) {
                    return $file->template() !== 'hover-background-image';
                });
                ?>
                
                <?php if($visibleFiles->count() > 0): ?>
                    <div class="finder-item-files">
                        <?php foreach($visibleFiles as $file): ?>
                            <div class="finder-file" data-url="<?= $file->url() ?>">
                                <?php if($file->type() == 'image'): ?>
                                    <span class="file-icon">🖼️</span>
                                <?php elseif($file->type() == 'video'): ?>
                                    <span class="file-icon">🎬</span>
                                <?php elseif($file->type() == 'document'): ?>
                                    <span class="file-icon">📄</span>
                                <?php else: ?>
                                    <span class="file-icon">📎</span>
                                <?php endif ?>
                                <span class="file-name"><?= $file->filename() ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            <?php endif ?>

            <!-- Zeige Unterseiten/Subfolders -->
            <?php if($item->hasChildren()): ?>
                <?php 
                $visibleChildren = $item->children()->visible();
                if($visibleChildren->count() > 0): 
                ?>
                    <div class="finder-item-subfolders">
                        <?php snippet('finder', ['pages' => $visibleChildren]) ?>
                    </div>
                <?php endif ?>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</div>

<style>
/* Hover Background Effect */
.finder-item:hover {
    /* Der Hover-Background wird via JavaScript gesetzt */
}

.finder-item[data-hover-bg]:hover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    opacity: 0.3;
    z-index: -1;
    pointer-events: none;
}
</style>

<script>
// Setze Hover-Backgrounds dynamisch
document.querySelectorAll('.finder-item[data-hover-bg]').forEach(item => {
    const hoverBg = item.getAttribute('data-hover-bg');
    if (hoverBg) {
        item.addEventListener('mouseenter', function() {
            this.style.setProperty('--hover-bg', `url(${hoverBg})`);
        });
    }
});
</script>