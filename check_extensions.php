<?php
/**
 * Extension Check für HEIC-Konvertierung
 * Führen Sie dieses Skript aus, um zu prüfen, ob alle benötigten Extensions verfügbar sind
 */

echo "=== HEIC-Konvertierung Extension Check ===\n\n";

// Prüfe PHP-Version
echo "PHP Version: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
    echo "✅ PHP Version ist kompatibel\n";
} else {
    echo "❌ PHP Version ist zu alt (mindestens 8.1.0 erforderlich)\n";
}

echo "\n";

// Prüfe Imagick Extension
if (extension_loaded('imagick')) {
    echo "✅ Imagick Extension ist verfügbar\n";
    
    // Prüfe Imagick Version
    $imagickVersion = \Imagick::getVersion();
    echo "   Imagick Version: " . $imagickVersion['versionString'] . "\n";
    
    // Prüfe unterstützte Formate
    $formats = \Imagick::queryFormats();
    if (in_array('HEIC', $formats)) {
        echo "✅ HEIC-Format wird unterstützt\n";
    } else {
        echo "❌ HEIC-Format wird NICHT unterstützt\n";
        echo "   Verfügbare Formate: " . implode(', ', array_slice($formats, 0, 10)) . "...\n";
    }
    
    if (in_array('JPEG', $formats)) {
        echo "✅ JPEG-Format wird unterstützt\n";
    } else {
        echo "❌ JPEG-Format wird NICHT unterstützt\n";
    }
    
} else {
    echo "❌ Imagick Extension ist NICHT verfügbar\n";
    echo "   Installation: brew install imagemagick\n";
    echo "   PHP Extension: pecl install imagick\n";
}

echo "\n";

// Prüfe Fileinfo Extension
if (extension_loaded('fileinfo')) {
    echo "✅ Fileinfo Extension ist verfügbar\n";
} else {
    echo "❌ Fileinfo Extension ist NICHT verfügbar\n";
}

echo "\n";

// Prüfe Schreibberechtigungen
$testDir = __DIR__ . '/content';
if (is_writable($testDir)) {
    echo "✅ Content-Verzeichnis ist beschreibbar\n";
} else {
    echo "❌ Content-Verzeichnis ist NICHT beschreibbar\n";
    echo "   Pfad: " . $testDir . "\n";
}

echo "\n";

// Test-HEIC-Konvertierung (falls Imagick verfügbar)
if (extension_loaded('imagick')) {
    echo "=== Test HEIC-Konvertierung ===\n";
    
    try {
        // Erstelle eine Test-HEIC-Datei (falls vorhanden)
        $testHeicFile = __DIR__ . '/test.heic';
        $testJpgFile = __DIR__ . '/test.jpg';
        
        if (file_exists($testHeicFile)) {
            echo "Test-HEIC-Datei gefunden, führe Konvertierung durch...\n";
            
            $imagick = new \Imagick($testHeicFile);
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompressionQuality(85);
            $imagick->writeImage($testJpgFile);
            $imagick->destroy();
            
            if (file_exists($testJpgFile)) {
                echo "✅ Test-Konvertierung erfolgreich\n";
                unlink($testJpgFile); // Aufräumen
            } else {
                echo "❌ Test-Konvertierung fehlgeschlagen\n";
            }
        } else {
            echo "Keine Test-HEIC-Datei gefunden\n";
        }
    } catch (Exception $e) {
        echo "❌ Test-Konvertierung fehlgeschlagen: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Check abgeschlossen ===\n"; 