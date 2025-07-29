# Mathis Neuhaus Portfolio - Finder Interface

Ein modernes Portfolio-Interface im Stil des macOS Finders, das Mathis Neuhaus' Arbeiten präsentiert.

## 🚀 Technologien

### Frontend
- **Vue.js 3** - Progressive JavaScript Framework
- **CSS3** - Moderne Styling mit Flexbox und Grid
- **HTML5** - Semantisches Markup

### Backend
- **Kirby CMS 5** - File-basiertes Content Management System
- **PHP 8+** - Server-side Logic

### APIs
- **REST API** - Kirby's eingebaute API
- **Custom Endpoints** - Erweiterte API-Routen für spezielle Funktionen

## 📁 Projektstruktur

```
mathisneuhaus_02/
├── components/                      # Vue.js Komponenten
│   ├── FinderInterface.vue         # Finder Interface Container
│   ├── FinderColumn.vue            # Einzelne Spalte
│   ├── FinderItem.vue              # Einzelnes Item (Ordner/Datei)
│   └── ContactOverlay.vue          # About/Contact Overlay
├── assets/                         # Statische Assets
│   ├── css/                        # Stylesheets
│   │   └── main.css               # Haupt-Stylesheet
│   ├── fonts/                      # Custom Fonts
│   │   └── KarlST-Regular.otf     # Karl Font
│   └── icons/                      # SVG Icons
│       ├── Folder.svg              # Ordner-Icon
│       └── Textfile.svg            # Textdatei-Icon
├── content/                        # Kirby Content
│   ├── 1_journalismus/            # Journalismus Ordner
│   ├── 2_communication/           # Kommunikation Ordner
│   ├── 3_consulting/              # Consulting Ordner
│   ├── 4_kuration/                # Kuration Ordner
│   ├── 5_redaktion/               # Redaktion Ordner
│   ├── 6_en-journalismus-docx/    # EN Journalismus Dateien
│   ├── 7_de-journalismus-docx/    # DE Journalismus Dateien
│   ├── 8_contact/                 # Contact/About Daten
│   └── *.jpg, *.png               # Medien-Dateien
├── kirby/                         # Kirby CMS Core
├── site/                          # Kirby Site-Konfiguration
│   ├── blueprints/                # CMS Blueprints
│   │   ├── pages/                 # Seiten-Templates
│   │   │   ├── folder.yml         # Ordner-Blueprint
│   │   │   ├── textfile.yml       # Textdatei-Blueprint
│   │   │   ├── externallink.yml   # Link-Blueprint
│   │   │   └── about.yml          # About-Blueprint
│   │   └── site.yml               # Haupt-Blueprint
│   ├── config/                    # Kirby Konfiguration
│   │   └── config.php             # API-Routen & Einstellungen
│   ├── templates/                 # PHP Templates
│   │   ├── default.php            # Haupt-Template
│   │   ├── about.php              # About Template
│   │   ├── finder.php             # Finder Template
│   │   ├── document.php           # Dokument Template
│   │   ├── image.php              # Bild Template
│   │   ├── link.php               # Link Template
│   │   └── project.php            # Projekt Template
│   └── snippets/                  # PHP Snippets
└── pages/                         # Kirby Pages
```

## 🔧 Wie es funktioniert

### 1. **Content Management (Kirby CMS)**
- **Blueprints**: Definieren die Struktur von Ordnern, Textdateien und Links
- **Content**: Alle Inhalte werden als `.txt` Dateien gespeichert
- **Media**: Bilder und andere Dateien werden direkt im `content/` Ordner gespeichert

### 2. **API Layer (PHP)**
```php
// site/config/config.php
'api' => [
    'routes' => [
        'content' => 'GET /api/content',           // Hauptinhalt
        'content/(:all)' => 'GET /api/content/*',  // Unterordner
        'contact' => 'GET /api/contact',           // About/Contact Info
        'desktop-images' => 'GET /api/desktop-images' // Hintergrundbilder
    ]
]
```

### 3. **Frontend (Vue.js)**
```javascript
// Multi-Column Navigation
const columns = ref([])
const handleItemClick = async (item, columnIndex) => {
    if (item.type === 'folder') {
        // Neue Spalte öffnen
        const response = await loadContent(item.path)
        columns.value.push({ items: response.items })
    }
}
```

### 4. **Icon System**
- **CSS Icons**: Ordner und Textdateien verwenden CSS-basierte Icons
- **Thumbnails**: Bilder zeigen 24x24px Thumbnails
- **Emoji Fallback**: Andere Dateitypen verwenden Emoji

## 🎨 Design System

### **Finder Interface**
- **Header**: "Mathis Neuhaus" (klickbar für About)
- **Spalten**: Multi-Column Navigation wie macOS Finder
- **Items**: Ordner, Dateien und Links mit Icons

### **About Overlay**
- **Schwarzes Design**: Terminal-ähnliches Fenster
- **"Files" Header**: Mit Ordner-Icon
- **Weißer Text**: Große, zentrierte Typografie

### **Responsive Design**
- **Desktop**: Multi-Column Layout
- **Mobile**: Single-Column mit Touch-Optimierung

## 📋 API Endpoints

### `GET /api/content`
Liefert alle Ordner und Dateien im Root-Verzeichnis.

**Response:**
```json
{
  "status": "ok",
  "path": "/",
  "items": [
    {
      "name": "Journalismus",
      "type": "folder",
      "path": "/journalismus",
      "item_count": 4
    },
    {
      "name": "EN_Journalismus.docx",
      "type": "textfile",
      "path": "/en-journalismus-docx"
    }
  ]
}
```

### `GET /api/content/(:all)`
Liefert den Inhalt eines spezifischen Ordners.

### `GET /api/contact`
Liefert About/Contact Informationen.

**Response:**
```json
{
  "status": "ok",
  "about": {
    "name": "Mathis Neuhaus",
    "subtitle": "Journalist and so much more",
    "location": "Based in Berlin",
    "phone": "+49 900 20 202 301",
    "email": "hello@mathisneuhaus.de"
  }
}
```

## 🔄 Datei-Abhängigkeiten

### **Vue.js App**
- **Abhängigkeiten**: `components/FinderItem.vue`, `components/FinderColumn.vue`
- **API**: `site/config/config.php`
- **Assets**: `assets/icons/`

### **Kirby CMS**
- **Blueprints**: `site/blueprints/` → definieren CMS-Struktur
- **Templates**: `site/templates/` → PHP-Rendering
- **Config**: `site/config/config.php` → API-Routen

### **Content Flow**
1. **Content** → `content/` Ordner
2. **Kirby** → Verarbeitet Content via Blueprints
3. **API** → `site/config/config.php` liefert JSON
4. **Vue.js** → Components rendern Interface
5. **Components** → `components/` für UI-Elemente

## 🛠️ Entwicklung

### **Lokale Entwicklung**
```bash
# Kirby Setup
composer install

# API Testing
curl http://localhost/api/content
```

### **Content Management**
- **Panel**: `http://localhost/panel` (Kirby Admin)
- **Blueprints**: Definieren neue Content-Typen
- **Templates**: Anpassen der PHP-Rendering

### **Styling**
- **CSS**: `assets/css/main.css`
- **Fonts**: Custom Karl Font
- **Icons**: CSS-basiert oder SVG
- **Responsive**: Mobile-first Design

## 🎯 Features

### **✅ Implementiert**
- [x] Multi-Column Finder Navigation
- [x] Custom CSS Icons für Ordner/Dateien
- [x] Bild-Thumbnails
- [x] About Overlay mit schwarzem Design
- [x] Responsive Design
- [x] API-basierte Content-Verwaltung
- [x] Kirby CMS Integration
- [x] Custom Karl Font Integration

### **🔄 Geplant**
- [ ] Datei-Vorschau Modal
- [ ] Suchfunktion
- [ ] Keyboard Navigation
- [ ] Drag & Drop
- [ ] Dark/Light Mode Toggle

## 📝 Wartung

### **Content hinzufügen**
1. Neuen Ordner in `content/` erstellen
2. Blueprint in `site/blueprints/pages/` anpassen
3. API-Route in `site/config/config.php` erweitern

### **Styling ändern**
1. CSS in `assets/css/main.css` bearbeiten
2. Icons in `assets/icons/` aktualisieren
3. Responsive Design testen

### **API erweitern**
1. Neue Route in `site/config/config.php` hinzufügen
2. Vue.js Components entsprechend anpassen
3. Error Handling implementieren

---

**Entwickelt für Mathis Neuhaus** | **Technologie: Vue.js + Kirby CMS** | **Design: macOS Finder Style**
