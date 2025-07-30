<?php

use Kirby\Cms\Page;

Kirby::plugin('your-name/batch-publish', [
    'panel' => [
        'buttons' => [
            'batch-publish' => function (Page $page) {
                // Nur für Ordner anzeigen
                if ($page->intendedTemplate()->name() !== 'folder') {
                    return null;
                }
                
                return [
                    'label' => 'Alle veröffentlichen',
                    'icon' => 'check',
                    'theme' => 'positive',
                    'dialog' => 'batch-publish-dialog',
                    'disabled' => !$page->permissions()->can('changeStatus')
                ];
            }
        ],
        'dialogs' => [
            'batch-publish-dialog' => [
                'load' => function (string $id) {
                    $page = page($id);
                    
                    if (!$page) {
                        return [
                            'component' => 'k-error-dialog',
                            'props' => [
                                'message' => 'Seite nicht gefunden'
                            ]
                        ];
                    }
                    
                    return [
                        'component' => 'k-form-dialog',
                        'props' => [
                            'fields' => [
                                'confirm' => [
                                    'label' => 'Bestätigung',
                                    'type' => 'info',
                                    'text' => 'Dies wird alle Seiten und Unterseiten in "' . $page->title() . '" veröffentlichen. Sind Sie sicher?'
                                ]
                            ],
                            'submitButton' => 'Alle veröffentlichen',
                            'value' => [
                                'confirm' => true
                            ]
                        ]
                    ];
                },
                'submit' => function (string $id) {
                    $page = page($id);
                    
                    if (!$page) {
                        return [
                            'status' => 'error',
                            'message' => 'Seite nicht gefunden'
                        ];
                    }
                    
                    // Rekursiv alle Kinder und deren Kinder veröffentlichen
                    batchPublishPages($page);
                    
                    return [
                        'event' => 'page.changeStatus',
                        'message' => 'Alle Seiten wurden erfolgreich veröffentlicht'
                    ];
                }
            ]
        ]
    ]
]);

// Hilfsfunktion zum rekursiven Veröffentlichen
function batchPublishPages(Page $page): void
{
    // Aktuelle Seite veröffentlichen, falls sie ein Draft ist
    if ($page->status() === 'draft') {
        try {
            $page->changeStatus('listed');
        } catch (Exception $e) {
            // Fehler ignorieren und weitermachen
        }
    }
    
    // Alle Kinder rekursiv veröffentlichen
    foreach ($page->children() as $child) {
        batchPublishPages($child);
    }
    
    // Alle Drafts veröffentlichen
    foreach ($page->drafts() as $draft) {
        try {
            $draft->changeStatus('listed');
        } catch (Exception $e) {
            // Fehler ignorieren und weitermachen
        }
    }
} 