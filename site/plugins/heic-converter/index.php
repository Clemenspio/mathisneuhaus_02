<?php

use Kirby\Filesystem\F;

Kirby::plugin('custom/heic-converter', [
    'hooks' => [
        'file.create:after' => function ($file) {
            // Prüfe ob es eine HEIC-Datei ist
            if (strtolower($file->extension()) === 'heic') {
                try {
                    // Log für Debugging
                    kirby()->log('heic-conversion')->info('HEIC file detected: ' . $file->filename());
                    
                    // Prüfe ob Imagick verfügbar ist
                    if (!extension_loaded('imagick')) {
                        kirby()->log('heic-conversion')->error('Imagick extension not available');
                        return;
                    }
                    
                    // Prüfe ob Imagick HEIC unterstützt
                    $formats = \Imagick::queryFormats();
                    if (!in_array('HEIC', $formats)) {
                        kirby()->log('heic-conversion')->error('HEIC format not supported by Imagick');
                        return;
                    }
                    
                    $heicPath = $file->root();
                    
                    // Validiere Dateipfad
                    if (!file_exists($heicPath) || !is_readable($heicPath)) {
                        kirby()->log('heic-conversion')->error('HEIC file not accessible: ' . $heicPath);
                        return;
                    }
                    
                    // Neuer Pfad für JPEG
                    $jpegPath = preg_replace('/\.heic$/i', '.jpg', $heicPath);
                    
                    // Prüfe Schreibberechtigung
                    $jpegDir = dirname($jpegPath);
                    if (!is_writable($jpegDir)) {
                        kirby()->log('heic-conversion')->error('Cannot write to directory: ' . $jpegDir);
                        return;
                    }

                    // HEIC → JPEG konvertieren
                    $imagick = new \Imagick($heicPath);
                    $imagick->setImageFormat('jpeg');
                    $imagick->setImageCompressionQuality(85);
                    $imagick->writeImage($jpegPath);
                    $imagick->destroy();

                    // Prüfe ob Konvertierung erfolgreich war
                    if (file_exists($jpegPath)) {
                        kirby()->log('heic-conversion')->info('Conversion successful: ' . $jpegPath);
                        
                        // Original HEIC löschen
                        F::remove($heicPath);
                        
                        // Neues File-Objekt registrieren
                        $file->update(['filename' => $file->name() . '.jpg']);
                        
                        kirby()->log('heic-conversion')->info('HEIC file replaced with JPEG');
                    } else {
                        kirby()->log('heic-conversion')->error('JPEG conversion failed - file not created');
                    }
                } catch (Exception $e) {
                    kirby()->log('heic-conversion')->error('Conversion error: ' . $e->getMessage());
                }
            }
        }
    ],
    
    // Registriere HEIC als gültigen Dateityp
    'fileTypes' => [
        'heic' => [
            'mime' => 'image/heic',
            'extensions' => ['heic', 'heif']
        ]
    ]
]);
