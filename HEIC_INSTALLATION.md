# HEIC-Konvertierung Installation

## ✅ Voraussetzungen erfüllt!

Das HEIC-Plugin verwendet `sips` (macOS eingebaute Bildkonvertierung), das bereits auf Ihrem System verfügbar ist.

## Plugin-Funktionalität

Das HEIC-Plugin funktioniert automatisch:

1. **Upload**: HEIC-Dateien können über das Kirby-Panel hochgeladen werden
2. **Konvertierung**: Automatische Konvertierung zu JPEG beim Upload mit `sips`
3. **Ersetzung**: Original HEIC-Datei wird durch JPEG ersetzt
4. **Logging**: Alle Vorgänge werden in Kirby-Logs protokolliert

## Blueprint-Konfiguration

HEIC-Dateien sind in folgenden Blueprints erlaubt:
- `site/blueprints/site.yml` (Media Files)
- `site/blueprints/pages/folder.yml` (Uploads)

## Testen

### 1. Test-Skript ausführen
```bash
php test_sips.php
```

### 2. HEIC-Datei hochladen
1. Öffnen Sie das Kirby-Panel
2. Navigieren Sie zu einem Ordner
3. Laden Sie eine HEIC-Datei hoch
4. Die Datei sollte automatisch zu JPEG konvertiert werden

### 3. Logs prüfen
```bash
tail -f site/logs/heic-conversion.log
```

## Troubleshooting

### Problem: "HEIC file not accessible"
**Lösung**: Schreibberechtigungen für content-Verzeichnis prüfen:
```bash
chmod 755 content/
chmod 755 content/*/
```

### Problem: "Cannot write to directory"
**Lösung**: Schreibberechtigungen für das Zielverzeichnis prüfen

### Problem: Konvertierung schlägt fehl
**Lösung**: Logs prüfen:
```bash
tail -f site/logs/heic-conversion.log
```

## Technische Details

- **Konvertierungstool**: `sips` (macOS eingebaute Bildkonvertierung)
- **Ausgabeformat**: JPEG mit hoher Qualität
- **Sicherheit**: Vollständige Pfadvalidierung und Escaping
- **Logging**: Detaillierte Protokollierung aller Vorgänge

## Vorteile der sips-Lösung

1. **Keine zusätzlichen Abhängigkeiten** - sips ist bereits auf macOS installiert
2. **Hohe Qualität** - Native macOS-Bildkonvertierung
3. **Schnell** - Optimiert für macOS
4. **Zuverlässig** - Getestet und stabil

## Logs

Das Plugin erstellt detaillierte Logs unter:
- `site/logs/heic-conversion.log`

## Beispiel-Logs

```
[2025-01-29 16:30:15] HEIC file detected: test.heic
[2025-01-29 16:30:16] Conversion successful: /path/to/test.jpg
[2025-01-29 16:30:16] HEIC file replaced with JPEG
``` 