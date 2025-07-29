# HEIC-Konvertierung Installation

## Voraussetzungen

### 1. Imagick Extension installieren

**Auf macOS mit Homebrew:**
```bash
# ImageMagick installieren
brew install imagemagick

# PHP Imagick Extension installieren
pecl install imagick
```

**Auf Ubuntu/Debian:**
```bash
# ImageMagick installieren
sudo apt-get update
sudo apt-get install imagemagick libmagickwand-dev

# PHP Imagick Extension installieren
sudo pecl install imagick
```

### 2. PHP-Konfiguration prüfen

Fügen Sie die Imagick-Extension zu Ihrer `php.ini` hinzu:
```ini
extension=imagick
```

### 3. Extension-Check ausführen

```bash
php check_extensions.php
```

## Plugin-Funktionalität

Das HEIC-Plugin funktioniert automatisch:

1. **Upload**: HEIC-Dateien können über das Kirby-Panel hochgeladen werden
2. **Konvertierung**: Automatische Konvertierung zu JPEG beim Upload
3. **Ersetzung**: Original HEIC-Datei wird durch JPEG ersetzt
4. **Logging**: Alle Vorgänge werden in Kirby-Logs protokolliert

## Blueprint-Konfiguration

HEIC-Dateien sind in folgenden Blueprints erlaubt:
- `site/blueprints/site.yml` (Media Files)
- `site/blueprints/pages/folder.yml` (Uploads)

## Troubleshooting

### Problem: "Imagick extension not available"
**Lösung**: Imagick-Extension installieren (siehe oben)

### Problem: "HEIC format not supported"
**Lösung**: ImageMagick mit HEIF-Unterstützung installieren:
```bash
brew install imagemagick --with-heif
```

### Problem: "Cannot write to directory"
**Lösung**: Schreibberechtigungen für content-Verzeichnis prüfen:
```bash
chmod 755 content/
chmod 755 content/*/
```

### Problem: Konvertierung schlägt fehl
**Lösung**: Logs prüfen:
```bash
tail -f site/logs/heic-conversion.log
```

## Logs

Das Plugin erstellt detaillierte Logs unter:
- `site/logs/heic-conversion.log`

## Testen

1. HEIC-Datei über Kirby-Panel hochladen
2. Datei sollte automatisch zu JPEG konvertiert werden
3. Original HEIC-Datei wird gelöscht
4. JPEG-Datei ist im Panel verfügbar 