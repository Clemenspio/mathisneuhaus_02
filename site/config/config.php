<?php

return [
    'debug' => false,
    
    'api' => [
        'basicAuth' => false,
        'allowInsecure' => false,
        'routes' => [
            [
                'pattern' => 'content',
                'method' => 'GET',
                'auth' => false,
                'action' => function () {
                    $site = site();
                    $items = [];
                    
                    // Get all folders
                    foreach ($site->children() as $child) {
                        // Skip hidden folders (starting with _)
                        if (str_starts_with($child->slug(), '_')) {
                            continue;
                        }
                        
                        if ($child->intendedTemplate() == 'folder') {
                            $item = [
                                'name' => $child->title()->value(),
                                'type' => 'folder',
                                'path' => '/' . $child->slug(),
                                'icon' => 'folder-icon',
                                'item_count' => $child->children()->count() + $child->files()->count()
                            ];
                            
                            if ($child->hover_background_images()->isNotEmpty() && $child->hover_background_images()->toFile()) {
                                $item['hover_thumbnail_url'] = $child->hover_background_images()->toFile()->url();
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
                    
                    // Add media files from root - FILTERED!
                    foreach ($site->files() as $file) {
                        // Skip files that are in the desktop-images folder
                        if ($file->parent()->slug() === '_desktop-images') {
                            continue;
                        }
                        
                        // NEU: Template-Check für hover-background-image
                        if ($file->template() === 'hover-background-image') {
                            continue;
                        }
                        
                        // Existing check for hover images (als zusätzliche Sicherheit)
                        $isHoverImage = false;
                        foreach ($site->children() as $child) {
                            if ($child->intendedTemplate() == 'folder' && $child->hover_background_images()->isNotEmpty()) {
                                $hoverFile = $child->hover_background_images()->toFile();
                                if ($hoverFile && $hoverFile->id() === $file->id()) {
                                    $isHoverImage = true;
                                    break;
                                }
                            }
                        }
                        
                        if ($isHoverImage) {
                            continue;
                        }
                        
                        $fileItem = [
                            'name' => $file->filename(),
                            'type' => $file->type(),
                            'path' => '/' . $file->filename(),
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
                    
                    return [
                        'status' => 'ok',
                        'path' => '/',
                        'items' => $items
                    ];
                }
            ],
            
            [
                'pattern' => 'content/(:all)',
                'method' => 'GET',
                'auth' => false,
                'action' => function ($path) {
                    $currentPage = page($path);
                    
                    if (!$currentPage) {
                        return [
                            'status' => 'error',
                            'message' => 'Path not found'
                        ];
                    }
                    
                    $items = [];
                    
                    foreach ($currentPage->children() as $child) {
                        // Skip hidden folders
                        if (str_starts_with($child->slug(), '_')) {
                            continue;
                        }
                        
                        if ($child->intendedTemplate() == 'folder') {
                            $item = [
                                'name' => $child->title()->value(),
                                'type' => 'folder',
                                'path' => '/' . $child->id(),
                                'icon' => 'folder-icon',
                                'item_count' => $child->children()->count() + $child->files()->count()
                            ];
                            
                            if ($child->hover_background_images()->isNotEmpty() && $child->hover_background_images()->toFile()) {
                                $item['hover_thumbnail_url'] = $child->hover_background_images()->toFile()->url();
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
                    
                    // Add media files - FILTERED!
                    foreach ($currentPage->files() as $file) {
                        // Skip files that are in the desktop-images folder
                        if ($file->parent()->slug() === '_desktop-images') {
                            continue;
                        }
                        
                        // NEU: Template-Check für hover-background-image
                        if ($file->template() === 'hover-background-image') {
                            continue;
                        }
                        
                        // Check if file is used as hover image in current page
                        $isHoverImage = false;
                        
                        if ($currentPage->hover_background_images()->isNotEmpty()) {
                            $hoverFile = $currentPage->hover_background_images()->toFile();
                            if ($hoverFile && $hoverFile->id() === $file->id()) {
                                $isHoverImage = true;
                            }
                        }
                        
                        // Also check children of current page
                        if (!$isHoverImage) {
                            foreach ($currentPage->children() as $child) {
                                if ($child->intendedTemplate() == 'folder' && $child->hover_background_images()->isNotEmpty()) {
                                    $hoverFile = $child->hover_background_images()->toFile();
                                    if ($hoverFile && $hoverFile->id() === $file->id()) {
                                        $isHoverImage = true;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        if ($isHoverImage) {
                            continue;
                        }
                        
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
                    
                    return [
                        'status' => 'ok',
                        'path' => '/' . $path,
                        'items' => $items
                    ];
                }
            ],
            
            [
                'pattern' => 'contact',
                'method' => 'GET',
                'auth' => false,
                'action' => function () {
                    $contact = page('contact');
                    
                    if (!$contact) {
                        return [
                            'status' => 'error',
                            'message' => 'Contact page not found'
                        ];
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
                    
                    return [
                        'status' => 'ok',
                        'email' => $contact->email()->value(),
                        'phone' => $contact->phone()->value(),
                        'address' => $contact->address()->value(),
                        'social' => $social,
                        'about' => [
                            'name' => $contact->about_name()->value(),
                            'subtitle' => $contact->about_subtitle()->value(),
                            'location' => $contact->about_location()->value(),
                            'phone' => $contact->about_phone()->value(),
                            'email' => $contact->about_email()->value()
                        ]
                    ];
                }
            ],
            
            [
                'pattern' => 'about',
                'method' => 'GET',
                'auth' => false,
                'action' => function () {
                    $about = page('about');
                    
                    if (!$about) {
                        return [
                            'status' => 'error',
                            'message' => 'About page not found'
                        ];
                    }
                    
                    return [
                        'status' => 'ok',
                        'title' => $about->title()->value(),
                        'content' => $about->about_text()->value()
                    ];
                }
            ],
            
            [
                'pattern' => 'desktop-images',
                'method' => 'GET',
                'auth' => false,
                'action' => function () {
                    $images = [];
                    
                    $desktopPath = kirby()->root('content') . '/_desktop-images';
                    
                    if (is_dir($desktopPath)) {
                        $files = scandir($desktopPath);
                        
                        foreach ($files as $file) {
                            if ($file !== '.' && $file !== '..' && !str_ends_with($file, '.txt')) {
                                $filePath = $desktopPath . '/' . $file;
                                if (is_file($filePath)) {
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                        $images[] = [
                                            'url' => '/content/_desktop-images/' . urlencode($file),
                                            'filename' => $file,
                                            'size' => filesize($filePath)
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    
                    return [
                        'status' => 'ok',
                        'message' => 'Found ' . count($images) . ' images',
                        'images' => $images
                    ];
                }
            ]
        ]
    ],
    
    'thumbs' => [
        'srcsets' => [
            'default' => [
                '300w' => ['width' => 300, 'quality' => 80],
                '600w' => ['width' => 600, 'quality' => 80],
                '900w' => ['width' => 900, 'quality' => 80],
                '1200w' => ['width' => 1200, 'quality' => 80]
            ]
        ]
    ]
];