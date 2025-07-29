<?php

Kirby::plugin('yoursite/api', [
    'routes' => [
        [
            'pattern' => 'custom-api/content',
            'method' => 'GET',
            'action'  => function () {
                $site = site();
                $items = [];
                
                foreach ($site->children() as $child) {
                    if ($child->intendedTemplate() == 'folder') {
                        $item = [
                            'name' => $child->title()->value(),
                            'type' => 'folder',
                            'path' => '/' . $child->slug(),
                            'icon' => 'folder-icon',
                            'item_count' => $child->children()->count() + $child->files()->count()
                        ];
                        
                        if ($child->hover_image()->isNotEmpty() && $child->hover_image()->toFile()) {
                            $item['hover_thumbnail_url'] = $child->hover_image()->toFile()->url();
                        }
                        
                        $items[] = $item;
                    }
                }
                
                return Response::json([
                    'status' => 'ok',
                    'path' => '/',
                    'items' => $items
                ]);
            }
        ],
        [
            'pattern' => 'custom-api/content/(:all)',
            'method' => 'GET',
            'action'  => function ($path) {
                $currentPage = page($path);
                
                if (!$currentPage) {
                    return Response::json([
                        'status' => 'error',
                        'message' => 'Path not found'
                    ], 404);
                }
                
                $items = [];
                
                // Add subfolders
                foreach ($currentPage->children() as $child) {
                    if ($child->intendedTemplate() == 'folder') {
                        $item = [
                            'name' => $child->title()->value(),
                            'type' => 'folder',
                            'path' => '/' . $child->id(),
                            'icon' => 'folder-icon',
                            'item_count' => $child->children()->count() + $child->files()->count()
                        ];
                        
                        if ($child->hover_image()->isNotEmpty() && $child->hover_image()->toFile()) {
                            $item['hover_thumbnail_url'] = $child->hover_image()->toFile()->url();
                        }
                        
                        $items[] = $item;
                    } elseif ($child->intendedTemplate() == 'textfile') {
                        $items[] = [
                            'name' => $child->title()->value(),
                            'type' => 'textfile',
                            'path' => '/' . $child->id(),
                            'icon' => 'text-file-icon',
                            'content' => $child->content()->value()
                        ];
                    } elseif ($child->intendedTemplate() == 'externallink') {
                        $items[] = [
                            'name' => $child->title()->value(),
                            'type' => 'externallink',
                            'path' => '/' . $child->id(),
                            'icon' => 'link-icon',
                            'url' => $child->link_url()->value(),
                            'external' => true
                        ];
                    }
                }
                
                // Add media files
                foreach ($currentPage->files() as $file) {
                    $fileItem = [
                        'name' => $file->filename(),
                        'type' => $file->type(),
                        'path' => '/' . $currentPage->id() . '/' . $file->filename(),
                        'url' => $file->url(),
                        'size' => $file->niceSize()
                    ];
                    
                    if ($file->type() == 'image') {
                        $fileItem['thumbnail'] = $file->resize(300)->url();
                        $fileItem['dimensions'] = $file->width() . 'x' . $file->height();
                    }
                    
                    $fileItem['icon'] = match($file->type()) {
                        'image' => 'image-icon',
                        'document' => 'pdf-icon',
                        'audio' => 'audio-icon',
                        'video' => 'video-icon',
                        default => 'file-icon'
                    };
                    
                    $items[] = $fileItem;
                }
                
                return Response::json([
                    'status' => 'ok',
                    'path' => '/' . $path,
                    'items' => $items
                ]);
            }
        ],
        [
            'pattern' => 'custom-api/contact',
            'method' => 'GET',
            'action'  => function () {
                $contact = page('contact');
                
                if (!$contact) {
                    return Response::json([
                        'status' => 'error',
                        'message' => 'Contact page not found'
                    ], 404);
                }
                
                $social = [];
                if ($contact->social()->isNotEmpty()) {
                    foreach ($contact->social()->toStructure() as $link) {
                        $social[] = [
                            'platform' => $link->platform()->value(),
                            'url' => $link->url()->value()
                        ];
                    }
                }
                
                return Response::json([
                    'status' => 'ok',
                    'email' => $contact->email()->value(),
                    'phone' => $contact->phone()->value(),
                    'address' => $contact->address()->value(),
                    'social' => $social
                ]);
            }
        ]
    ]
]);