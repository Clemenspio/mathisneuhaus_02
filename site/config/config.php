<?php

return [
    'debug' => true,
    'api' => [
        'basicAuth' => false,
        'allowInsecure' => true,
    'routes' => [
        [
                'pattern' => 'content',
                'method' => 'GET',
                'auth' => false,
                'action'  => function () {
                    $site = site();
                    $items = [];
                    
                    // Get all folders (nicht nur listed)
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
                    

                    
                    // Add media files from root (excluding desktop background images)
                    foreach ($site->files() as $file) {
                        // Skip desktop background images
                        $filename = strtolower($file->filename());
                        $isDesktopImage = strpos($filename, 'bliss') !== false ||
                                        strpos($filename, 'wallpaper') !== false ||
                                        strpos($filename, 'background') !== false ||
                                        strpos($filename, 'desktop') !== false ||
                                        strpos($filename, 'landscape') !== false ||
                                        strpos($filename, 'surreal') !== false ||
                                        strpos($filename, 'furry') !== false ||
                                        strpos($filename, 'thelma') !== false ||
                                        strpos($filename, 'afc') !== false ||
                                        strpos($filename, 'dhmkm6m') !== false ||
                                        strpos($filename, 'diiim79') !== false ||
                                        strpos($filename, 'dj41tdd') !== false ||
                                        strpos($filename, 'dfyprxy') !== false ||
                                        strpos($filename, 'dizg1m2') !== false ||
                                        strpos($filename, 'dg748ej') !== false ||
                                        strpos($filename, 'dh8kpaa') !== false ||
                                        strpos($filename, 'diudtq9') !== false ||
                                        strpos($filename, 'cool-300x212') !== false;
                        
                        // Allow some images for testing thumbnails
                        $isTestImage = strpos($filename, 'cool-2560x1600') !== false;
                        
                        if (!$isDesktopImage || $isTestImage) {
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
                'action'  => function ($path) {
                    $currentPage = page($path);
                    
                    if (!$currentPage) {
                        return [
                            'status' => 'error',
                            'message' => 'Path not found'
                        ];
                }

                $items = [];

                    // Add subfolders and pages
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
                'action'  => function () {
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
                            'action'  => function () {
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
                'action'  => function () {
                    $site = site();
                    $images = [];
                    
                    // Get images that are specifically uploaded for desktop backgrounds
                    // These are uploaded via the Panel under "Desktop Images" > "Background Images"
                    foreach ($site->files() as $file) {
                        if (in_array($file->type(), ['image'])) {
                            // Check if this is a desktop background image by filename patterns
                            $filename = strtolower($file->filename());
                            $isDesktopImage = strpos($filename, 'bliss') !== false ||
                                            strpos($filename, 'wallpaper') !== false ||
                                            strpos($filename, 'background') !== false ||
                                            strpos($filename, 'desktop') !== false ||
                                            strpos($filename, 'landscape') !== false ||
                                            strpos($filename, 'surreal') !== false ||
                                            strpos($filename, 'furry') !== false ||
                                            strpos($filename, 'thelma') !== false ||
                                            strpos($filename, 'afc') !== false ||
                                            strpos($filename, 'dhmkm6m') !== false ||
                                            strpos($filename, 'diiim79') !== false ||
                                            strpos($filename, 'dj41tdd') !== false ||
                                            strpos($filename, 'dfyprxy') !== false ||
                                            strpos($filename, 'dizg1m2') !== false ||
                                            strpos($filename, 'dg748ej') !== false ||
                                            strpos($filename, 'dh8kpaa') !== false ||
                                            strpos($filename, 'diudtq9') !== false;
                            
                            if ($isDesktopImage) {
                                $images[] = [
                                    'url' => $file->url(),
                                    'filename' => $file->filename(),
                                    'dimensions' => $file->width() . 'x' . $file->height(),
                                    'size' => $file->niceSize()
                                ];
                            }
                        }
                    }
                    
                    return [
                        'status' => 'ok',
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