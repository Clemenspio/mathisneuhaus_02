<?php

use Kirby\Filesystem\F;

Kirby::plugin('custom/heic-converter', [
    'hooks' => [
        'file.create:after' => function ($file) {
            if ($file->extension() === 'heic') {
                try {
                    // Pfad zur hochgeladenen HEIC-Datei
                    $heicPath = $file->root();
                    // Neuer Pfad für JPEG
                    $jpegPath = preg_replace('/\.heic$/i', '.jpg', $heicPath);

                    // HEIC → JPEG konvertieren (Imagick benötigt)
                    $imagick = new \Imagick($heicPath);
                    $imagick->setImageFormat('jpeg');
                    $imagick->setImageCompressionQuality(85);
                    $imagick->writeImage($jpegPath);
                    $imagick->destroy();

                    // Original HEIC löschen
                    F::remove($heicPath);

                    // Neues File-Objekt registrieren
                    $file->update(['filename' => $file->name() . '.jpg']);
                } catch (Exception $e) {
                    kirby()->log('heic-conversion')->error($e->getMessage());
                }
            }
        }
    ]
]);
